<?php

/**
 * Parse a human name into parts.
 *
 * Based on Josh Fraser's library:
 * https://github.com/joshfraser/PHP-Name-Parser
 *
 * @author Chris Ullyott <chris@monkdevelopment.com>
 */

namespace ChrisUllyott;

class HumanNameParser
{
    /**
     * The original full name to be parsed.
     *
     * @var string
     */
    private $nameString;

    /**
     * The full name with standard replacements made.
     *
     * @var string
     */
    private $nameStringClean;

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
    private static $prefixes = [
        'Mr.'        => ['mr', 'mister'],
        'Ms.'        => ['ms', 'miss'],
        'Mrs.'       => ['mrs', 'missus', 'missis'],
        'Dr.'        => ['dr', 'doctor'],
        'Fr.'        => ['fr', 'father'],
        'Sr.'        => ['sr', 'sister'],
        'Sir'        => ['sir'],
        'Pastor'     => ['pastor'],
        'Chaplain'   => ['chaplain'],
        'Elder'      => ['elder'],
        'Deacon'     => ['deacon'],
        'Bishop'     => ['bishop'],
        'Archbishop' => ['archbishop'],
        'Cardinal'   => ['cardinal'],
        'Most'       => ['most'],
        'very'       => ['very'],
        'Rev.'       => ['reverend', 'rev'],
        'Hon.'       => ['honorable'],
        'Pres.'      => ['president'],
        'Gov.'       => ['governor','governer'],
        'Ofc.'       => ['officer'],
        'Msgr.'      => ['monsignor'],
        'Br.'        => ['brother'],
        'Supt.'      => ['superintendent'],
        'Rep.'       => ['representatitve'],
        'Sen.'       => ['senator'],
        'Amb.'       => ['ambassador'],
        'Treas.'     => ['treasurer'],
        'Sec.'       => ['secretary'],
        'Pvt.'       => ['private'],
        'Cpl.'       => ['corporal'],
        'Sgt.'       => ['sargent'],
        'Adm.'       => ['administrative', 'administrator'],
        'Maj.'       => ['major'],
        'Capt.'      => ['captain'],
        'Cmdr.'      => ['commander'],
        'Lt.'        => ['lieutenant'],
        'Lt. Col.'   => ['lieutenant colonel'],
        'Col.'       => ['colonel'],
        'Gen.'       => ['general'],
        'ArtD.'      => ['doctor of arts'],
        'D.Div.'     => ['doctor of divinity'],
        'MD.'        => ['doctor of general medicine'],
        'DVM.'       => ['doctor of veterinary medine'],
        'PaedDr.'    => ['doctor of education'],
        'PharmDr.'   => ['doctor of pharmacy'],
        'PhDr.'      => ['doctor of philosophy'],
        'PhMr.'      => ['master of pharmacy'],
        'RCDr.'      => ['doctor of business studies'],
        'DSc.'       => ['doctor of science'],
        'RSDr.'      => ['doctor of socio-political sciences'],
        'RTDr.'      => ['doctor of technical sciences'],
        'Th.D.'      => ['doctor of theology'],
        'ThLic.'     => ['licentiate of theology'],
        'ThMgr.'     => ['master of theology', 'master of divinity'],
        'DiS.'       => ['certified specialist'],
        'Prof.'      => ['prof', 'professor'],
        'As.'        => ['assistant'],
        'Odb. As.'   => ['assistant professor'],
        'Doc.'       => ['associate professor']
    ];

    /**
     * Compound last name terms.
     *
     * @var array
     */
    private static $compounds = [
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
    ];

    /**
     * Ancestry line suffixes.
     *
     * @var array
     */
    private static $lineSuffixes = [
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
    ];

    /**
     * Professional title suffixes.
     *
     * @var array
     */
    private static $proSuffixes = [
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
        'HDip', 'B.Bus.', 'E.S.C.P.', 'D.Div.'
    ];

    /**
     * Constructor.
     *
     * @param string $nameString The full name to be parsed
     */
    public function __construct($nameString)
    {
        $this->nameString = $nameString;
    }

    /**
     * Get all parts that were parsed from the name.
     *
     * @return array
     */
    public function parse()
    {
        return [
            'full'       => $this->getFullName(),
            'salutation' => $this->getSalutation(),
            'first'      => $this->getFirstName(),
            'middle'     => $this->getMiddleName(),
            'last'       => $this->getLastName(),
            'suffix'     => $this->getSuffix()
        ];
    }

    /**
     * Get the original full name to be parsed.
     *
     * @return string
     */
    public function getNameString()
    {
        return $this->nameString;
    }

    /**
     * Get the original name with standard cleanups applied.
     *
     * @return string
     */
    public function getNameStringClean()
    {
        if (!$this->nameStringClean) {
            $this->nameStringClean = self::sanitize($this->getNameString());
            $this->nameStringClean = self::rewrite($this->nameStringClean);
            $this->nameStringClean = ucwords($this->nameStringClean);
        }

        return $this->nameStringClean;
    }

    /**
     * Get the full, formatted name.
     *
     * @var array
     */
    public function getFullName()
    {
        $parts = [
            $this->getSalutation(),
            $this->getFirstName(),
            $this->getMiddleName(),
            $this->getLastName(),
            $this->getSuffix()
        ];

        return implode(' ', array_filter($parts));
    }

    /**
     * Get the salutation prefix of a name.
     *
     * @return string
     */
    public function getSalutation()
    {
        if (is_null($this->salutation)) {
            $this->salutation = $this->extractSalutation($this->getNameStringClean());
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
        if (is_null($this->firstName)) {
            $this->firstName = $this->extractFirstName($this->getNameStringClean());
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
        if (is_null($this->middleName)) {
            $this->middleName = $this->extractMiddleName($this->getNameStringClean());
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
        if (is_null($this->lastName)) {
            $this->lastName = $this->extractLastName($this->getNameStringClean());
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
        if (is_null($this->suffix)) {
            $this->suffix = $this->extractSuffix($this->getNameStringClean());
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
        if ($this->getSuffix()) {
            $string = str_replace(" {$this->getSuffix()}", '', $string);
        }

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
        $nameParts = explode(' ', $string);

        return array_shift($nameParts);
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

        $replace = [
            $this->getFirstName(),
            $this->getLastName()
        ];

        $middle = trim(str_replace($replace, '', $string));

        if (strlen(trim($middle, '.')) === 1) {
            return strtoupper(trim($middle, '.')) . '.';
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
     * Extract the suffixes from a name string. Start at the end of the name and
     * match as many suffixes as possible.
     *
     * @param  string $string The sanitized name string to work with
     * @return string
     */
    private function extractSuffix($string)
    {
        $suffix = '';

        $parts = array_reverse(explode(' ', $string));
        $suffixes = array_merge(self::$lineSuffixes, self::$proSuffixes);

        foreach ($parts as $part) {
            $found = '';

            foreach ($suffixes as $s) {
                if ($part === $s) {
                    $found = $s;
                    break;
                }
            }

            if ($found) {
                $suffix .= $found . ' ';
            } else {
                break;
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
        $replace = ['/[[:cntrl:]]/', '/\bthe\b/i', '/[,]/', '/\s+/'];

        return trim(preg_replace($replace, ' ', $string));
    }

    /**
     * Perform common replacements.
     *
     * @param  string $string The sanitized name string to work with
     * @return string
     */
    private static function rewrite($string)
    {
        $string = self::rewritePrefixes($string);
        $string = self::rewriteSuffixes($string);

        return $string;
    }

    /**
     * Rewrites known salutation prefixes to a standard format.
     *
     * @param  string $string The name string
     * @return string
     */
    private static function rewritePrefixes($string)
    {
        // Find matching prefixes.
        $matches = [];
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
        $pattern = '\b' . preg_quote(rtrim($term, '.')) . '\b\.?';

        return "/{$pattern}/i";
    }

    /**
     * Order an array by the string length of its keys, longest first.
     *
     * @param  array  $array  The array to operate on
     * @return array
     */
    private static function orderArrayByKeyLength(array $array)
    {
        uksort($array, function($a, $b){
            return strlen($b) - strlen($a);
        });

        return $array;
    }

    /**
     * Order an array by the string length of its values, longest first.
     *
     * @param  array  $array  The array to operate on
     * @return array
     */
    private static function orderArrayByValueLength(array $array)
    {
        usort($array, function($a, $b){
            return strlen($b) - strlen($a);
        });

        return $array;
    }
}
