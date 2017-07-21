<?php

/**
 * Parse a human name into parts.
 *
 * Based on Josh Fraser's library:
 * https://github.com/joshfraser/PHP-Name-Parser
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */

class HumanNameParser
{
    /**
     * The original full name to be parsed.
     *
     * @var string
     */
    private $fullName;

    /**
     * The full name with standard replacements made.
     *
     * @var string
     */
    private $fullNameClean;

    /**
     * The salutation prefix of a name.
     *
     * @var string
     */
    private $salutation;

    /**
     * The first name.
     *
     * @var string
     */
    private $firstName;

    /**
     * The middle name.
     *
     * @var string
     */
    private $middleName;

    /**
     * The last name.
     *
     * @var string
     */
    private $lastName;

    /**
     * The name suffix.
     *
     * @var string
     */
    private $suffix;

    /**
     * Common salutation prefixes and their variants.
     *
     * @var array
     */
    private static $prefixes = array(
        'Mr.'        => array('mr', 'mister'),
        'Ms.'        => array('ms', 'miss'),
        'Mrs.'       => array('mrs', 'missus', 'missis'),
        'Dr.'        => array('dr', 'doctor'),
        'Fr.'        => array('fr', 'father'),
        'Sr.'        => array('sr', 'sister'),
        'Sir'        => array('sir'),
        'Pastor'     => array('pastor'),
        'Chaplain'   => array('chaplain'),
        'Elder'      => array('elder'),
        'Deacon'     => array('deacon'),
        'Bishop'     => array('bishop'),
        'Archbishop' => array('archbishop'),
        'Cardinal'   => array('cardinal'),
        'Most'       => array('most'),
        'very'       => array('very'),
        'Rev.'       => array('reverend', 'rev'),
        'Hon.'       => array('honorable'),
        'Pres.'      => array('president'),
        'Gov.'       => array('governor','governer'),
        'Ofc.'       => array('officer'),
        'Msgr.'      => array('monsignor'),
        'Sr.'        => array('sister'),
        'Br.'        => array('brother'),
        'Supt.'      => array('superintendent'),
        'Rep.'       => array('representatitve'),
        'Sen.'       => array('senator'),
        'Amb.'       => array('ambassador'),
        'Treas.'     => array('treasurer'),
        'Sec.'       => array('secretary'),
        'Pvt.'       => array('private'),
        'Cpl.'       => array('corporal'),
        'Sgt.'       => array('sargent'),
        'Adm.'       => array('administrative', 'administrator'),
        'Maj.'       => array('major'),
        'Capt.'      => array('captain'),
        'Cmdr.'      => array('commander'),
        'Lt.'        => array('lieutenant'),
        'Lt. Col.'   => array('lieutenant colonel'),
        'Col.'       => array('colonel'),
        'Gen.'       => array('general'),
        'ArtD.'      => array('doctor of arts'),
        'MD.'        => array('doctor of general medicine'),
        'DVM.'       => array('doctor of veterinary medine'),
        'PaedDr.'    => array('doctor of education'),
        'PharmDr.'   => array('doctor of pharmacy'),
        'PhDr.'      => array('doctor of philosophy'),
        'PhMr.'      => array('master of pharmacy'),
        'RCDr.'      => array('doctor of business studies'),
        'DSc.'       => array('doctor of science'),
        'RSDr.'      => array('doctor of socio-political sciences'),
        'RTDr.'      => array('doctor of technical sciences'),
        'Th.D.'      => array('doctor of theology'),
        'ThLic.'     => array('licentiate of theology'),
        'ThMgr.'     => array('master of theology', 'master of divinity'),
        'DiS.'       => array('certified specialist'),
        'Prof.'      => array('prof', 'professor'),
        'As.'        => array('assistant'),
        'Odb. As.'   => array('assistant professor'),
        'Doc.'       => array('associate professor')
    );

    /**
     * Compound last name terms.
     *
     * @var array
     */
    private static $compounds = array(
        'Da',
        'De',
        'Del',
        'Della',
        'De La',
        'Dem',
        'Den',
        'Der',
        'Di',
        'Du',
        'Het',
        'La',
        'Onder',
        'Op',
        'Pietro',
        'St.',
        'St',
        "'T",
        'Ten',
        'Ter',
        'Van',
        'Vanden',
        'Vere',
        'Von'
    );

    /**
     * Ancestry line suffixes.
     *
     * @var array
     */
    private static $lineSuffixes = array(
        'I',
        'II',
        'III',
        'IV',
        'V',
        '1st',
        '2nd',
        '3rd',
        '4th',
        '5th',
        'Senior',
        'Junior',
        'Jr.',
        'Sr.'
    );

    /**
     * Professional title suffixes.
     *
     * @var array
     */
    private static $proSuffixes = array(
        'AO', 'B.A.', 'M.Sc', 'BCompt', 'PhD', 'Ph.D.', 'APR', 'RPh', 'PE', 'MD', 'M.D.',
        'MA', 'DMD', 'CME', 'BSc', 'Bsc', 'BSc(hons)', 'Ph.D.', 'BEng', 'M.B.A.', 'MBA',
        'FAICD', 'CM', 'OBC', 'M.B.', 'ChB', 'FRCP', 'FRSC', 'FREng', 'Esq', 'MEng',
        'MSc', 'J.D.', 'JD', 'BGDipBus', 'Dip', 'Dipl.Phys', 'M.H.Sc.', 'MPA', 'B.Comm',
        'B.Eng', 'B.Acc', 'FSA', 'PGDM', 'FCPA', 'RN', 'R.N.', 'MSN', 'PCA', 'PCCRM',
        'PCFP', 'PCGD', 'PCHR', 'PCM', 'PCPS', 'PCPM', 'PCSCM', 'PCSM', 'PCMM', 'PCTC', 'ACA',
        'FCA', 'ACMA', 'FCMA', 'AAIA', 'FAIA', 'CCC', 'MIPA', 'FIPA', 'CIA', 'CFE', 'CISA',
        'CFAP', 'QC', 'Q.C.', 'M.Tech', 'CTA', 'C.I.M.A.', 'B.Ec', 'CFIA', 'ICCP',
        'CPS', 'CAP-OM', 'CAPTA', 'TNAOAP', 'AFA', 'AVA', 'ASA', 'CAIA', 'CBA', 'CVA', 'ICVS',
        'CIIA', 'CMU', 'PFM', 'PRM', 'CFP', 'CWM', 'CCP', 'EA', 'CCMT', 'CGAP', 'CDFM', 'CFO',
        'CGFM', 'CGAT', 'CGFO', 'CMFO', 'CPFO', 'CPFA', 'BMD', 'BIET', 'P.Eng', 'PE', 'MBBS',
        'MB', 'BCh', 'BAO', 'BMBS', 'MBBChir', 'MBChBa', 'MPhil', 'LL.D', 'LLD',
        'D.Lit', 'DEA', 'DESS', 'DClinPsy', 'DSc', 'MRes', 'M.Res', 'Psy.D', 'Pharm.D',
        'BASS', 'BATheol', 'BBA', 'BBLS', 'BBS', 'BBus', 'BChem', 'BCJ', 'BCL', 'BCLD(SocSc)',
        'BClinSci', 'BCom', 'BCombSt', 'BCommEdCommDev', 'BComp', 'BComSc', 'BCoun', 'BD',
        'BDes', 'BE', 'BEcon', 'BEcon&Fin', 'M.P.P.M.', 'MPPM', 'BEconSci', 'BEd', 'BEng',
        'BES', 'BEng(Tech)', 'BFA', 'BFin', 'BFLS', 'BFST', 'BH', 'BHealthSc', 'BHSc', 'BHy',
        'BMid', 'BMin', 'BMS', 'BMSc', 'BMSc', 'BMS', 'BMus', 'BMusEd', 'BMusPerf', 'BN',
        'BNS', 'BNurs', 'BOptom', 'BPA', 'BPharm', 'BPhil', 'TTC', 'DIP', 'Tchg', 'BEd',
        'MEd', 'ACIB', 'FCIM', 'FCIS', 'FCS', 'Fcs', 'Bachelor', 'O.C.', 'JP', 'C.Eng',
        'C.P.A.', 'B.B.S.', 'MBE', 'GBE', 'KBE', 'DBE', 'CBE', 'OBE', 'MRICS',
        'BPhil(Ed)', 'BPhys', 'BPhysio', 'BPl', 'BRadiog', 'BSc', 'B.Sc', 'BScAgr',
        'BSc(Dairy)', 'BSc(MCRM)', 'CEng', 'FCA', 'CFA', 'C.F.A.', 'LLB',
        'LL.B', 'LLM', 'LL.M', 'CA(SA)', 'C.A.', 'CA', 'CPA',  'Solicitor',  'DMS',
        'FIWO', 'CEnv', 'MICE', 'MIWEM', 'B.Com', 'BCom', 'BAcc', 'BA', 'BEc', 'MEc',
        'HDip', 'B.Bus.', 'E.S.C.P.'
    );

    /**
     * Constructor.
     *
     * @param string $fullName The full name to be parsed
     */
    public function __construct($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * Get all parts that were parsed from the name.
     *
     * @return array
     */
    public function parse()
    {
        return array(
            'full'       => $this->fullName,
            'full_clean' => $this->getFullNameClean(),
            'salutation' => $this->getSalutation(),
            'first'      => $this->getFirstName(),
            'middle'     => $this->getMiddleName(),
            'last'       => $this->getLastName(),
            'suffix'     => $this->getSuffix()
        );
    }

    /**
     * Get the original full name.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Get the full name with standard replacements made.
     *
     * @return string
     */
    public function getFullNameClean()
    {
        if (!$this->fullNameClean) {
            $this->fullNameClean = self::sanitize($this->fullName);
            $this->fullNameClean = self::rewrite($this->fullNameClean);
            $this->fullNameClean = ucwords($this->fullNameClean);
        }

        return $this->fullNameClean;
    }

    /**
     * Get the salutation prefix of a name.
     *
     * @return string
     */
    public function getSalutation()
    {
        if (!$this->salutation) {
            $this->salutation = $this->extractSalutation($this->getFullNameClean());
        }

        return $this->salutation;
    }

    /**
     * Get the first name.
     *
     * @return string
     */
    public function getFirstName()
    {
        if (!$this->firstName) {
            $this->firstName = $this->extractFirstName($this->getFullNameClean());
        }

        return $this->firstName;
    }

    /**
     * Get the middle name.
     *
     * @return string
     */
    public function getMiddleName()
    {
        if (!$this->middleName) {
            $this->middleName = $this->extractMiddleName($this->getFullNameClean());
        }

        return $this->middleName;
    }

    /**
     * Get the last name.
     *
     * @return string
     */
    public function getLastName()
    {
        if (!$this->lastName) {
            $this->lastName = $this->extractLastName($this->getFullNameClean());
        }

        return $this->lastName;
    }

    /**
     * Get the name suffixes.
     *
     * @return string
     */
    public function getSuffix()
    {
        if (!$this->suffix) {
            $this->suffix = $this->extractSuffix($this->getFullNameClean());
        }

        return $this->suffix;
    }

    /**
     * Extract the salutation prefix from a name string.
     *
     * @param  string $string The sanitized name string to work with
     * @return string
     */
    private function extractSalutation($string)
    {
        $salutation = '';

        $parts = explode(' ', $string);
        $prefixes = array_keys(self::$prefixes);

        foreach ($parts as $part) {
            foreach ($prefixes as $prefix) {
                if ($part === $prefix) {
                    $salutation .= $part . ' ';
                    break;
                }
            }
        }

        return trim($salutation);
    }

    /**
     * Extract the first name from a name string.
     *
     * @param  string $string The sanitized name string to work with
     * @return string
     */
    private function extractFirstName($string)
    {
        if ($this->getSalutation()) {
            $string = str_replace("{$this->getSalutation()} ", '', $string);
        }

        return array_shift(explode(' ', $string));
    }

    /**
     * Extract the middle name from a name string.
     *
     * @param  string $string The sanitized name string to work with
     * @return string
     */
    private function extractMiddleName($string)
    {
        if ($this->getSalutation()) {
            $string = str_replace($this->getSalutation(), '', $string);
        }

        if ($this->getSuffix()) {
            $string = str_replace($this->getSuffix(), '', $string);
        }

        $replace = array(
            $this->getFirstName(),
            $this->getLastName()
        );

        $middle = trim(str_replace($replace, '', $string));

        if (strlen(trim($middle, '.')) === 1) {
            return strtoupper($middle) . '.';
        }

        return $middle;
    }

    /**
     * Extract the last name from a name string.
     *
     * @param  string $string The sanitized name string to work with
     * @return string
     */
    private function extractLastName($string)
    {
        // We may only have a first name in this case.
        if (strpos($string, ' ') === false) {
            return '';
        }

        if ($this->getSuffix()) {
            $string = trim(str_replace(" {$this->getSuffix()}", '', $string));
        }

        // Get the next rightmost term.
        $stringArr = explode(' ', $string);
        $last = array_pop($stringArr);

        // Get the next rightmost term (for compound last names).
        $string = implode(' ', $stringArr);
        $suffixes = self::orderArrayByValueLength(self::$compounds);

        foreach ($suffixes as $suffix) {
            $pattern = self::getTermPattern($suffix);

            if (preg_match($pattern, $string)) {
                $last = trim($suffix . ' ' . $last);
                break;
            }
        }

        return trim($last);
    }

    /**
     * Extract the suffixes from a name string.
     *
     * @param  string $string The sanitized name string to work with
     * @return string
     */
    private function extractSuffix($string)
    {
        $suffix = '';

        $parts = explode(' ', $string);
        $suffixes = array_merge(self::$lineSuffixes, self::$proSuffixes);

        foreach ($parts as $part) {
            foreach ($suffixes as $s) {
                if ($part === $s) {
                    $suffix .= $part . ' ';
                    break;
                }
            }
        }

        return trim($suffix);
    }

    /**
     * Sanitize a name string. Removes unwanted characters and "the", and
     * standardizes spacing.
     *
     * @param  string $string The name string
     * @return string
     */
    private static function sanitize($string)
    {
        $replace = array('/[[:cntrl:]]/', '/\bthe\b/i', '/[,]/', '/\s+/');

        return trim(preg_replace($replace, ' ', $string));
    }

    /**
     * Perform common replacements for prefixes and suffixes.
     *
     * @param  string $string The sanitized name string to work with
     * @return string
     */
    private static function rewrite($string)
    {
        $string = self::rewritePrefixes($string);
        $string = self::rewriteSuffixes($string);

        return  preg_replace('/\.+/', '.', $string);
    }

    /**
     * Rewrites known salutation prefixes to a standard format.
     *
     * "Lieutenant colonel" ==> "Lt. Col."
     *
     * @param  string $string The name string
     * @return string
     */
    private static function rewritePrefixes($string)
    {
        // Find matching prefixes.
        $matches = array();
        foreach (self::$prefixes as $prefix => $prefixVersions) {
            foreach ($prefixVersions as $prefixVersion) {
                $pattern = self::getTermPattern($prefixVersion);
                if (preg_match($pattern, $string)) {
                    $matches[$prefix] = $prefixVersion;
                }
            }
        }

        $matches = self::orderArrayByKeyLength($matches);

        // Replace all matches.
        foreach ($matches as $prefix => $prefixVersion) {
            $pattern = self::getTermPattern($prefixVersion);
            $string = preg_replace($pattern, $prefix, $string);
        }

        return $string;
    }

    /**
     * Rewrites known suffixes to a standard format.
     *
     * "phd" ==> "PhD"
     *
     * @param  string $string The name string
     * @return string
     */
    private static function rewriteSuffixes($string)
    {
        $suffixes = array_merge(self::$lineSuffixes, self::$proSuffixes);

        foreach ($suffixes as $suffix) {
            $pattern = self::getTermPattern($suffix);
            $string = preg_replace($pattern, $suffix, $string);
        }

        return $string;
    }

    /**
     * Get a pattern to match a name term with.
     *
     * @param  string $term The name term (suffix or prefix)
     * @return string
     */
    private static function getTermPattern($term)
    {
        $pattern = '\b' . preg_quote(trim($term, '.')) . '\b';

        return "/{$pattern}/i";
    }

    /**
     * Order an array by the string length of its keys.
     *
     * @param  array  $array     The array to operate on
     * @param  string $direction ASC|DESC
     * @return array
     */
    private static function orderArrayByKeyLength(array $array, $direction = 'DESC')
    {
        uksort($array, self::getCompareMethod($direction));

        return $array;
    }

    /**
     * Order an array by the string length of its values.
     *
     * @param  array  $array     The array to operate on
     * @param  string $direction ASC|DESC
     * @return array
     */
    private static function orderArrayByValueLength(array $array, $direction = 'DESC')
    {
        usort($array, self::getCompareMethod($direction));

        return $array;
    }

    /**
     * Get the name of the appropriate string comparison method.
     *
     * @param  string $direction ASC|DESC
     * @return string
     */
    private static function getCompareMethod($direction = 'ASC')
    {
        return 'self::lengthCompare' . ucfirst(strtolower($direction));
    }

    /**
     * Compare the length of two strings.
     */
    private static function lengthCompareAsc($a, $b)
    {
        return strlen($a) - strlen($b);
    }

    /**
     * Compare the length of two strings.
     */
    private static function lengthCompareDesc($a, $b)
    {
        return strlen($b) - strlen($a);
    }
}
