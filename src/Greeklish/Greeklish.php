<?php

namespace Otodev\Greeklish;

class Greeklish
{

    /**
     * Convert a Latin/greeklish characters text to its modern Greek equivalent
     *
     * @param string $text
     *
     * @return string
     */
    public static function toGreek($text)
    {
        return static::replaceText($text, static::greeklishToGreekMap());
    }

    /**
     * @param string $text
     * @param array $characterMap
     * @param bool $exactMatch
     * @param string $ignore
     *
     * @return string
     */
    protected static function replaceText(string $text, array $characterMap, bool $exactMatch = true, string $ignore = '')
    {
        if (is_string($text) && !empty($text)) {
            collect($characterMap)->each(function ($characters) use (&$text, $exactMatch, $ignore) {
                $regex = $exactMatch ? $characters->find : '[' . $characters->find . ']';
                if ($ignore) $regex = '(?![' . $ignore . '])' . $regex;
                $text = mb_ereg_replace("($regex)", $characters->replace, $text);
            });

            return $text;
        }

        return '';
    }

    /**
     * Greeklish to GreekMap
     *
     * @return array
     */
    protected static function greeklishToGreekMap()
    {
        return [
            (object)['find' => 'tha', 'replace' => 'θα'],
            (object)['find' => 'the', 'replace' => 'θε'],
            (object)['find' => 'thi', 'replace' => 'θι'],
            (object)['find' => 'thh', 'replace' => 'θη'],
            (object)['find' => 'tho', 'replace' => 'θο'],
            (object)['find' => '(thy|thu)', 'replace' => 'θυ'],
            (object)['find' => '(thw|thv)', 'replace' => 'θω'],
            (object)['find' => 'tH', 'replace' => 'τΗ'],
            (object)['find' => 'TH', 'replace' => 'ΤΗ'],
            (object)['find' => 'Th', 'replace' => 'Τη'],
            (object)['find' => 'th', 'replace' => 'τη'],
            (object)['find' => '(cH|ch)', 'replace' => 'χ'],
            (object)['find' => '(CH|Ch)', 'replace' => 'Χ'],
            (object)['find' => '(PS|Ps)', 'replace' => 'Ψ'],
            (object)['find' => '(ps|pS)', 'replace' => 'ψ'],
            (object)['find' => '(Ks|KS)', 'replace' => 'Ξ'],
            (object)['find' => '(ks|kS)', 'replace' => 'ξ'],
            (object)['find' => '(VR)', 'replace' => 'ΒΡ'],
            (object)['find' => '(vr|vR)', 'replace' => 'βρ'],
            (object)['find' => '(Vr)', 'replace' => 'Βρ'],
            (object)['find' => '8a', 'replace' => 'θα'],
            (object)['find' => '8e', 'replace' => 'θε'],
            (object)['find' => '8h', 'replace' => 'θη'],
            (object)['find' => '8i', 'replace' => 'θι'],
            (object)['find' => '8o', 'replace' => 'θο'],
            (object)['find' => '8y', 'replace' => 'θυ'],
            (object)['find' => '8u', 'replace' => 'θυ'],
            (object)['find' => '(8v|8w)', 'replace' => 'θω'],
            (object)['find' => '8A', 'replace' => 'ΘΑ'],
            (object)['find' => '8E', 'replace' => 'ΘΕ'],
            (object)['find' => '8H', 'replace' => 'ΘΗ'],
            (object)['find' => '8I', 'replace' => 'ΘΙ'],
            (object)['find' => '8O', 'replace' => 'ΘΟ'],
            (object)['find' => '(8Y|8U)', 'replace' => 'ΘΥ'],
            (object)['find' => '8V', 'replace' => 'ΘΩ'],
            (object)['find' => '8W', 'replace' => 'ΘΩ'],
            (object)['find' => '9a', 'replace' => 'θα'],
            (object)['find' => '9e', 'replace' => 'θε'],
            (object)['find' => '9h', 'replace' => 'θη'],
            (object)['find' => '9i', 'replace' => 'θι'],
            (object)['find' => '9o', 'replace' => 'θο'],
            (object)['find' => '9y', 'replace' => 'θυ'],
            (object)['find' => '9u', 'replace' => 'θυ'],
            (object)['find' => '(9v|9w)', 'replace' => 'θω'],
            (object)['find' => '9A', 'replace' => 'ΘΑ'],
            (object)['find' => '9E', 'replace' => 'ΘΕ'],
            (object)['find' => '9H', 'replace' => 'ΘΗ'],
            (object)['find' => '9I', 'replace' => 'ΘΙ'],
            (object)['find' => '9O', 'replace' => 'ΘΟ'],
            (object)['find' => '(9Y|9U)', 'replace' => 'ΘΥ'],
            (object)['find' => '9V', 'replace' => 'ΘΩ'],
            (object)['find' => '9W', 'replace' => 'ΘΩ'],
            (object)['find' => 's[\\n]', 'replace' => 'ς\n'],
            (object)['find' => 's[\\!]', 'replace' => 'ς!'],
            (object)['find' => 's[\\.]', 'replace' => 'ς.'],
            (object)['find' => 's[\\ ]', 'replace' => 'ς '],
            (object)['find' => 's[\\,]', 'replace' => 'ς,'],
            (object)['find' => 's[\\+]', 'replace' => 'ς+'],
            (object)['find' => 's[\\-]', 'replace' => 'ς-'],
            (object)['find' => 's[\\(]', 'replace' => 'ς('],
            (object)['find' => 's[\\)]', 'replace' => 'ς)'],
            (object)['find' => 's[\\[]', 'replace' => 'ς['],
            (object)['find' => 's[\\]]', 'replace' => 'ς]'],
            (object)['find' => 's[\\{]', 'replace' => 'ς{'],
            (object)['find' => 's[\\}]', 'replace' => 'ς}'],
            (object)['find' => 's[\\<]', 'replace' => 'ς<'],
            (object)['find' => 's[\\>]', 'replace' => 'ς>'],
            (object)['find' => 's[\\?]', 'replace' => 'ς;'],
            (object)['find' => 's[\\/]', 'replace' => 'ς/'],
            (object)['find' => 's[\\:]', 'replace' => 'ς:'],
            (object)['find' => 's[\\;]', 'replace' => 'ς;'],
            (object)['find' => 's[\\"]', 'replace' => 'ς"'],
            (object)['find' => 's[\\\']', 'replace' => 'ς\''],
            (object)['find' => 's[\\f]', 'replace' => 'ς\f'],
            (object)['find' => 's[\\r]', 'replace' => 'ς\r'],
            (object)['find' => 's[\\t]', 'replace' => 'ς\t'],
            (object)['find' => 's[\\v]', 'replace' => 'ς\v'],
            (object)['find' => 'rx', 'replace' => 'ρχ'],
            (object)['find' => 'sx', 'replace' => 'σχ'],
            (object)['find' => 'Sx', 'replace' => 'Σχ'],
            (object)['find' => 'SX', 'replace' => 'ΣΧ'],
            (object)['find' => 'ux', 'replace' => 'υχ'],
            (object)['find' => 'Ux', 'replace' => 'Υχ'],
            (object)['find' => 'UX', 'replace' => 'ΥΧ'],
            (object)['find' => 'yx', 'replace' => 'υχ'],
            (object)['find' => 'Yx', 'replace' => 'Υχ'],
            (object)['find' => 'YX', 'replace' => 'ΥΧ'],
            (object)['find' => '3a', 'replace' => 'ξα'],
            (object)['find' => '3e', 'replace' => 'ξε'],
            (object)['find' => '3h', 'replace' => 'ξη'],
            (object)['find' => '3i', 'replace' => 'ξι'],
            (object)['find' => '3ο', 'replace' => 'ξο'],
            (object)['find' => '3u', 'replace' => 'ξυ'],
            (object)['find' => '3y', 'replace' => 'ξυ'],
            (object)['find' => '3v', 'replace' => 'ξω'],
            (object)['find' => '3w', 'replace' => 'ξω'],
            (object)['find' => 'a3', 'replace' => 'αξ'],
            (object)['find' => 'e3', 'replace' => 'εξ'],
            (object)['find' => 'h3', 'replace' => 'ηξ'],
            (object)['find' => 'i3', 'replace' => 'ιξ'],
            (object)['find' => 'ο3', 'replace' => 'οξ'],
            (object)['find' => 'u3', 'replace' => 'υξ'],
            (object)['find' => 'y3', 'replace' => 'υξ'],
            (object)['find' => 'v3', 'replace' => 'ωξ'],
            (object)['find' => 'w3', 'replace' => 'ωξ'],
            (object)['find' => '3A', 'replace' => 'ξΑ'],
            (object)['find' => '3E', 'replace' => 'ξΕ'],
            (object)['find' => '3H', 'replace' => 'ξΗ'],
            (object)['find' => '3I', 'replace' => 'ξΙ'],
            (object)['find' => '3O', 'replace' => 'ξΟ'],
            (object)['find' => '3U', 'replace' => 'ξΥ'],
            (object)['find' => '3Y', 'replace' => 'ξΥ'],
            (object)['find' => '3V', 'replace' => 'ξΩ'],
            (object)['find' => '3W', 'replace' => 'ξΩ'],
            (object)['find' => 'A3', 'replace' => 'Αξ'],
            (object)['find' => 'E3', 'replace' => 'Εξ'],
            (object)['find' => 'H3', 'replace' => 'Ηξ'],
            (object)['find' => 'I3', 'replace' => 'Ιξ'],
            (object)['find' => 'O3', 'replace' => 'Οξ'],
            (object)['find' => 'U3', 'replace' => 'Υξ'],
            (object)['find' => 'Y3', 'replace' => 'Υξ'],
            (object)['find' => 'V3', 'replace' => 'Ωξ'],
            (object)['find' => 'W3', 'replace' => 'Ωξ'],
            (object)['find' => 'A', 'replace' => 'Α'],
            (object)['find' => 'a', 'replace' => 'α'],
            (object)['find' => 'B', 'replace' => 'Β'],
            (object)['find' => 'b', 'replace' => 'β'],
            (object)['find' => 'V', 'replace' => 'Β'],
            (object)['find' => 'v', 'replace' => 'β'],
            (object)['find' => 'c', 'replace' => 'ψ'],
            (object)['find' => 'C', 'replace' => 'Ψ'],
            (object)['find' => 'G', 'replace' => 'Γ'],
            (object)['find' => 'g', 'replace' => 'γ'],
            (object)['find' => 'D', 'replace' => 'Δ'],
            (object)['find' => 'd', 'replace' => 'δ'],
            (object)['find' => 'E', 'replace' => 'Ε'],
            (object)['find' => 'e', 'replace' => 'ε'],
            (object)['find' => 'Z', 'replace' => 'Ζ'],
            (object)['find' => 'z', 'replace' => 'ζ'],
            (object)['find' => 'H', 'replace' => 'Η'],
            (object)['find' => 'h', 'replace' => 'η'],
            (object)['find' => 'U', 'replace' => 'Θ'],
            (object)['find' => 'u', 'replace' => 'υ'],
            (object)['find' => 'I', 'replace' => 'Ι'],
            (object)['find' => 'i', 'replace' => 'ι'],
            (object)['find' => 'j', 'replace' => 'ξ'],
            (object)['find' => 'J', 'replace' => 'Ξ'],
            (object)['find' => 'K', 'replace' => 'Κ'],
            (object)['find' => 'k', 'replace' => 'κ'],
            (object)['find' => 'L', 'replace' => 'Λ'],
            (object)['find' => 'l', 'replace' => 'λ'],
            (object)['find' => 'M', 'replace' => 'Μ'],
            (object)['find' => 'm', 'replace' => 'μ'],
            (object)['find' => 'N', 'replace' => 'Ν'],
            (object)['find' => 'n', 'replace' => 'ν'],
            (object)['find' => 'X', 'replace' => 'Χ'],
            (object)['find' => 'x', 'replace' => 'χ'],
            (object)['find' => 'O', 'replace' => 'Ο'],
            (object)['find' => 'o', 'replace' => 'ο'],
            (object)['find' => 'P', 'replace' => 'Π'],
            (object)['find' => 'p', 'replace' => 'π'],
            (object)['find' => 'R', 'replace' => 'Ρ'],
            (object)['find' => 'r', 'replace' => 'ρ'],
            (object)['find' => 'S', 'replace' => 'Σ'],
            (object)['find' => 's', 'replace' => 'σ'],
            (object)['find' => 'T', 'replace' => 'Τ'],
            (object)['find' => 't', 'replace' => 'τ'],
            (object)['find' => 'Y', 'replace' => 'Υ'],
            (object)['find' => 'y', 'replace' => 'υ'],
            (object)['find' => 'F', 'replace' => 'Φ'],
            (object)['find' => 'f', 'replace' => 'φ'],
            (object)['find' => 'W', 'replace' => 'Ω'],
            (object)['find' => 'w', 'replace' => 'ω'],
            (object)['find' => '\\?', 'replace' => ';']
        ];
    }

    /**
     * Convert a modern Greek characters text to its greeklish equivalent
     *
     * @param string $text
     *
     * @return string
     */
    public static function toGreeklish($text)
    {
        return static::replaceText($text, static::greekToGreeklishMap());
    }

    /**
     * Greek to GreeklishMap
     *
     * @return array
     */
    protected static function greekToGreeklishMap()
    {
        return [
            (object)['find' => 'ΓΧ', 'replace' => 'GX'],
            (object)['find' => 'γχ', 'replace' => 'gx'],
            (object)['find' => 'ΤΘ', 'replace' => 'T8'],
            (object)['find' => 'τθ', 'replace' => 't8'],
            (object)['find' => '(θη|Θη)', 'replace' => '8h'],
            (object)['find' => 'ΘΗ', 'replace' => '8H'],
            (object)['find' => 'αυ', 'replace' => 'au'],
            (object)['find' => 'Αυ', 'replace' => 'Au'],
            (object)['find' => 'ΑΥ', 'replace' => 'AY'],
            (object)['find' => 'ευ', 'replace' => 'eu'],
            (object)['find' => 'εύ', 'replace' => 'eu'],
            (object)['find' => 'εϋ', 'replace' => 'ey'],
            (object)['find' => 'εΰ', 'replace' => 'ey'],
            (object)['find' => 'Ευ', 'replace' => 'Eu'],
            (object)['find' => 'Εύ', 'replace' => 'Eu'],
            (object)['find' => 'Εϋ', 'replace' => 'Ey'],
            (object)['find' => 'Εΰ', 'replace' => 'Ey'],
            (object)['find' => 'ΕΥ', 'replace' => 'EY'],
            (object)['find' => 'ου', 'replace' => 'ou'],
            (object)['find' => 'ού', 'replace' => 'ou'],
            (object)['find' => 'οϋ', 'replace' => 'oy'],
            (object)['find' => 'οΰ', 'replace' => 'oy'],
            (object)['find' => 'Ου', 'replace' => 'Ou'],
            (object)['find' => 'Ού', 'replace' => 'Ou'],
            (object)['find' => 'Οϋ', 'replace' => 'Oy'],
            (object)['find' => 'Οΰ', 'replace' => 'Oy'],
            (object)['find' => 'ΟΥ', 'replace' => 'OY'],
            (object)['find' => 'Α', 'replace' => 'A'],
            (object)['find' => 'α', 'replace' => 'a'],
            (object)['find' => 'ά', 'replace' => 'a'],
            (object)['find' => 'Ά', 'replace' => 'A'],
            (object)['find' => 'Β', 'replace' => 'B'],
            (object)['find' => 'β', 'replace' => 'b'],
            (object)['find' => 'Γ', 'replace' => 'G'],
            (object)['find' => 'γ', 'replace' => 'g'],
            (object)['find' => 'Δ', 'replace' => 'D'],
            (object)['find' => 'δ', 'replace' => 'd'],
            (object)['find' => 'Ε', 'replace' => 'E'],
            (object)['find' => 'ε', 'replace' => 'e'],
            (object)['find' => 'έ', 'replace' => 'e'],
            (object)['find' => 'Έ', 'replace' => 'E'],
            (object)['find' => 'Ζ', 'replace' => 'Z'],
            (object)['find' => 'ζ', 'replace' => 'z'],
            (object)['find' => 'Η', 'replace' => 'H'],
            (object)['find' => 'η', 'replace' => 'h'],
            (object)['find' => 'ή', 'replace' => 'h'],
            (object)['find' => 'Ή', 'replace' => 'H'],
            (object)['find' => 'Θ', 'replace' => 'TH'],
            (object)['find' => 'θ', 'replace' => 'th'],
            (object)['find' => 'Ι', 'replace' => 'I'],
            (object)['find' => 'Ϊ', 'replace' => 'I'],
            (object)['find' => 'ι', 'replace' => 'i'],
            (object)['find' => 'ί', 'replace' => 'i'],
            (object)['find' => 'ΐ', 'replace' => 'i'],
            (object)['find' => 'ϊ', 'replace' => 'i'],
            (object)['find' => 'Ί', 'replace' => 'I'],
            (object)['find' => 'Κ', 'replace' => 'K'],
            (object)['find' => 'κ', 'replace' => 'k'],
            (object)['find' => 'Λ', 'replace' => 'L'],
            (object)['find' => 'λ', 'replace' => 'l'],
            (object)['find' => 'Μ', 'replace' => 'M'],
            (object)['find' => 'μ', 'replace' => 'm'],
            (object)['find' => 'Ν', 'replace' => 'N'],
            (object)['find' => 'ν', 'replace' => 'n'],
            (object)['find' => 'Ξ', 'replace' => 'KS'],
            (object)['find' => 'ξ', 'replace' => 'ks'],
            (object)['find' => 'Ο', 'replace' => 'O'],
            (object)['find' => 'ο', 'replace' => 'o'],
            (object)['find' => 'Ό', 'replace' => 'O'],
            (object)['find' => 'ό', 'replace' => 'o'],
            (object)['find' => 'Π', 'replace' => 'P'],
            (object)['find' => 'π', 'replace' => 'p'],
            (object)['find' => 'Ρ', 'replace' => 'R'],
            (object)['find' => 'ρ', 'replace' => 'r'],
            (object)['find' => 'Σ', 'replace' => 'S'],
            (object)['find' => 'σ', 'replace' => 's'],
            (object)['find' => 'Τ', 'replace' => 'T'],
            (object)['find' => 'τ', 'replace' => 't'],
            (object)['find' => 'Υ', 'replace' => 'Y'],
            (object)['find' => 'Ύ', 'replace' => 'Y'],
            (object)['find' => 'Ϋ', 'replace' => 'Y'],
            (object)['find' => 'ΰ', 'replace' => 'y'],
            (object)['find' => 'ύ', 'replace' => 'y'],
            (object)['find' => 'ϋ', 'replace' => 'y'],
            (object)['find' => 'υ', 'replace' => 'y'],
            (object)['find' => 'Φ', 'replace' => 'F'],
            (object)['find' => 'φ', 'replace' => 'f'],
            (object)['find' => 'Χ', 'replace' => 'X'],
            (object)['find' => 'χ', 'replace' => 'x'],
            (object)['find' => 'Ψ', 'replace' => 'Ps'],
            (object)['find' => 'ψ', 'replace' => 'ps'],
            (object)['find' => 'Ω', 'replace' => 'w'],
            (object)['find' => 'ω', 'replace' => 'w'],
            (object)['find' => 'Ώ', 'replace' => 'w'],
            (object)['find' => 'ώ', 'replace' => 'w'],
            (object)['find' => 'ς', 'replace' => 's'],
            (object)['find' => ';', 'replace' => '?']
        ];
    }
}
