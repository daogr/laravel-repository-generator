<?php

    namespace Otodev\Greeklish;

    use Illuminate\Support\Str;

    class Greekstr {

        /**
         * @param $str
         *
         * @return string
         */
        public static function toLatin2($str) {
            return self::toGreeklish(Str::upper(self::sanitizeDiacritics($str)));
        }

        /**
         * @param $str
         *
         * @return string
         */
        public static function toGreek2($str) {
            return self::toGreek(Str::upper(self::sanitizeDiacritics($str)));
        }

        /**
         * Convert a modern/ancient Greek characters text containing diacritics to its simple equivalent without diacritics.
         *
         * @param string $text
         *
         * @return string
         */
        public static function sanitizeDiacritics($text) {
            return static::replaceText($text, static::diacriticsMap(), false);
        }

        /**
         * Convert a Latin/greeklish characters text to its modern Greek equivalent
         *
         * @param string $text
         *
         * @return string
         */
        public static function toGreek($text) {
            return static::replaceText($text, static::greeklishToGreekMap());
        }

        /**
         * Convert a modern Greek characters text to its greeklish equivalent
         *
         * @param string $text
         *
         * @return string
         */
        public static function toGreeklish($text) {
            return static::replaceText($text, static::greekToGreeklishMap());
        }

        /**
         * Diacritics Map
         *
         * @return array
         */
        protected static function diacriticsMap() {
            return [
                (object) ['find' => 'άἀἁἂἃἄἅἆἇὰάᾀᾁᾂᾃᾄᾅᾆᾇᾰᾱᾲᾳᾴᾶᾷ', 'replace' => 'α'],
                (object) ['find' => 'ΆἈἉἊἋἌἍἎἏᾈᾉᾊᾋᾌᾍᾎᾏᾸᾹᾺΆᾼ', 'replace' => 'Α'],
                (object) ['find' => 'έἐἑἒἓἔἕὲέ', 'replace' => 'ε'],
                (object) ['find' => 'ΈἘἙἚἛἜἝ', 'replace' => 'Ε'],
                (object) ['find' => 'ήἠἡἢἣἤἥἦἧῆὴῇ', 'replace' => 'η'],
                (object) ['find' => 'ΉἨἩἪἫἬἭἮἯ', 'replace' => 'Η'],
                (object) ['find' => 'ίἰἱἲἳἴἵὶῖ', 'replace' => 'ι'],
                (object) ['find' => 'ΊἶἷἸἹἺἻἼἽἾἿ', 'replace' => 'Ι'],
                (object) ['find' => 'όὀὁὂὃὄὅὸ', 'replace' => 'ο'],
                (object) ['find' => 'ΌὈὉὊὋὌὍ', 'replace' => 'Ο'],
                (object) ['find' => 'ύὐὑὒὓὔὕὖὗ', 'replace' => 'υ'],
                (object) ['find' => 'ΎὙὛὝὟ', 'replace' => 'Υ'],
                (object) ['find' => 'ώὠὡὢὣὤὥὦὧῶ', 'replace' => 'ω'],
                (object) ['find' => 'ΏὨὩὪὫὬὭὮὯ', 'replace' => 'Ω']
            ];
        }

        /**
         * Greek to GreeklishMap
         *
         * @return array
         */
        protected static function greekToGreeklishMap() {
            return [
                (object) ['find' => 'Α', 'replace' => 'A'],
                (object) ['find' => 'α', 'replace' => 'a'],
                (object) ['find' => 'ά', 'replace' => 'a'],
                (object) ['find' => 'Ά', 'replace' => 'A'],
                (object) ['find' => 'Β', 'replace' => 'B'],
                (object) ['find' => 'β', 'replace' => 'b'],
                (object) ['find' => 'Γ', 'replace' => 'G'],
                (object) ['find' => 'γ', 'replace' => 'g'],
                (object) ['find' => 'Δ', 'replace' => 'D'],
                (object) ['find' => 'δ', 'replace' => 'd'],
                (object) ['find' => 'Ε', 'replace' => 'E'],
                (object) ['find' => 'ε', 'replace' => 'e'],
                (object) ['find' => 'έ', 'replace' => 'e'],
                (object) ['find' => 'Έ', 'replace' => 'E'],
                (object) ['find' => 'Ζ', 'replace' => 'Z'],
                (object) ['find' => 'ζ', 'replace' => 'z'],
                (object) ['find' => 'Η', 'replace' => 'H'],
                (object) ['find' => 'η', 'replace' => 'h'],
                (object) ['find' => 'ή', 'replace' => 'h'],
                (object) ['find' => 'Ή', 'replace' => 'H'],
                (object) ['find' => 'Θ', 'replace' => ''],
                (object) ['find' => 'θ', 'replace' => ''],
                (object) ['find' => 'Ι', 'replace' => 'I'],
                (object) ['find' => 'Ϊ', 'replace' => 'I'],
                (object) ['find' => 'ι', 'replace' => 'i'],
                (object) ['find' => 'ί', 'replace' => 'i'],
                (object) ['find' => 'ΐ', 'replace' => 'i'],
                (object) ['find' => 'ϊ', 'replace' => 'i'],
                (object) ['find' => 'Ί', 'replace' => 'I'],
                (object) ['find' => 'Κ', 'replace' => 'K'],
                (object) ['find' => 'κ', 'replace' => 'k'],
                (object) ['find' => 'Λ', 'replace' => 'L'],
                (object) ['find' => 'λ', 'replace' => 'l'],
                (object) ['find' => 'Μ', 'replace' => 'M'],
                (object) ['find' => 'μ', 'replace' => 'm'],
                (object) ['find' => 'Ν', 'replace' => 'N'],
                (object) ['find' => 'ν', 'replace' => 'n'],
                (object) ['find' => 'Ξ', 'replace' => 'X'],
                (object) ['find' => 'ξ', 'replace' => 'x'],
                (object) ['find' => 'Ο', 'replace' => 'O'],
                (object) ['find' => 'ο', 'replace' => 'o'],
                (object) ['find' => 'Ό', 'replace' => 'O'],
                (object) ['find' => 'ό', 'replace' => 'o'],
                (object) ['find' => 'Π', 'replace' => 'P'],
                (object) ['find' => 'π', 'replace' => 'p'],
                (object) ['find' => 'Ρ', 'replace' => 'R'],
                (object) ['find' => 'ρ', 'replace' => 'r'],
                (object) ['find' => 'Σ', 'replace' => 'S'],
                (object) ['find' => 'σ', 'replace' => 's'],
                (object) ['find' => 'Τ', 'replace' => 'T'],
                (object) ['find' => 'τ', 'replace' => 't'],
                (object) ['find' => 'Υ', 'replace' => 'Y'],
                (object) ['find' => 'Ύ', 'replace' => 'Y'],
                (object) ['find' => 'Ϋ', 'replace' => 'Y'],
                (object) ['find' => 'ΰ', 'replace' => 'y'],
                (object) ['find' => 'ύ', 'replace' => 'y'],
                (object) ['find' => 'ϋ', 'replace' => 'y'],
                (object) ['find' => 'υ', 'replace' => 'y'],
                (object) ['find' => 'Φ', 'replace' => 'F'],
                (object) ['find' => 'φ', 'replace' => 'f'],
                (object) ['find' => 'Χ', 'replace' => 'X'],
                (object) ['find' => 'χ', 'replace' => 'x'],
                (object) ['find' => 'Ψ', 'replace' => ''],
                (object) ['find' => 'ψ', 'replace' => ''],
                (object) ['find' => 'Ω', 'replace' => 'w'],
                (object) ['find' => 'ω', 'replace' => 'w'],
                (object) ['find' => 'Ώ', 'replace' => 'w'],
                (object) ['find' => 'ώ', 'replace' => 'w'],
                (object) ['find' => 'ς', 'replace' => 's'],
            ];
        }

        /**
         * Greeklish to GreekMap
         *
         * @return array
         */
        protected static function greeklishToGreekMap() {
            return [
                (object) ['find' => 'A', 'replace' => 'Α'],
                (object) ['find' => 'a', 'replace' => 'α'],
                (object) ['find' => 'B', 'replace' => 'Β'],
                (object) ['find' => 'b', 'replace' => 'β'],
                (object) ['find' => 'V', 'replace' => 'Β'],
                (object) ['find' => 'v', 'replace' => 'β'],
                (object) ['find' => 'c', 'replace' => 'ψ'],
                (object) ['find' => 'C', 'replace' => 'Ψ'],
                (object) ['find' => 'G', 'replace' => 'Γ'],
                (object) ['find' => 'g', 'replace' => 'γ'],
                (object) ['find' => 'D', 'replace' => 'Δ'],
                (object) ['find' => 'd', 'replace' => 'δ'],
                (object) ['find' => 'E', 'replace' => 'Ε'],
                (object) ['find' => 'e', 'replace' => 'ε'],
                (object) ['find' => 'Z', 'replace' => 'Ζ'],
                (object) ['find' => 'z', 'replace' => 'ζ'],
                (object) ['find' => 'H', 'replace' => 'Η'],
                (object) ['find' => 'h', 'replace' => 'η'],
                (object) ['find' => 'U', 'replace' => 'Θ'],
                (object) ['find' => 'u', 'replace' => 'υ'],
                (object) ['find' => 'I', 'replace' => 'Ι'],
                (object) ['find' => 'i', 'replace' => 'ι'],
                (object) ['find' => 'j', 'replace' => 'ξ'],
                (object) ['find' => 'J', 'replace' => 'Ξ'],
                (object) ['find' => 'K', 'replace' => 'Κ'],
                (object) ['find' => 'k', 'replace' => 'κ'],
                (object) ['find' => 'L', 'replace' => 'Λ'],
                (object) ['find' => 'l', 'replace' => 'λ'],
                (object) ['find' => 'M', 'replace' => 'Μ'],
                (object) ['find' => 'm', 'replace' => 'μ'],
                (object) ['find' => 'N', 'replace' => 'Ν'],
                (object) ['find' => 'n', 'replace' => 'ν'],
                (object) ['find' => 'X', 'replace' => 'Χ'],
                (object) ['find' => 'x', 'replace' => 'χ'],
                (object) ['find' => 'O', 'replace' => 'Ο'],
                (object) ['find' => 'o', 'replace' => 'ο'],
                (object) ['find' => 'P', 'replace' => 'Π'],
                (object) ['find' => 'p', 'replace' => 'π'],
                (object) ['find' => 'R', 'replace' => 'Ρ'],
                (object) ['find' => 'r', 'replace' => 'ρ'],
                (object) ['find' => 'S', 'replace' => 'Σ'],
                (object) ['find' => 's', 'replace' => 'σ'],
                (object) ['find' => 'T', 'replace' => 'Τ'],
                (object) ['find' => 't', 'replace' => 'τ'],
                (object) ['find' => 'Y', 'replace' => 'Υ'],
                (object) ['find' => 'y', 'replace' => 'υ'],
                (object) ['find' => 'F', 'replace' => 'Φ'],
                (object) ['find' => 'f', 'replace' => 'φ'],
                (object) ['find' => 'W', 'replace' => 'Ω'],
                (object) ['find' => 'w', 'replace' => 'ω'],
            ];
        }

        /**
         * @param string $text
         * @param array  $characterMap
         * @param bool   $exactMatch
         * @param string $ignore
         *
         * @return string
         */
        protected static function replaceText(string $text, array $characterMap, bool $exactMatch = true, string $ignore = '') {
            if(is_string($text) && !empty($text)) {
                collect($characterMap)->each(function($characters) use (&$text, $exactMatch, $ignore) {
                    $regex = $exactMatch ? $characters->find : '[' . $characters->find . ']';
                    if($ignore) $regex = '(?![' . $ignore . '])' . $regex;
                    $text = mb_ereg_replace("($regex)", $characters->replace, $text);
                });

                return $text;
            }

            return '';
        }
    }
