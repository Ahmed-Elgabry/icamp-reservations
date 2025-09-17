<?php
namespace App\Helpers;

class Sanitizer
{
    /**
     * Sanitize an associative array of input values.
     *
     * @param array $data
     * @param array $allowHtmlFields Fields that may contain limited HTML
     * @return array
     */
    public static function sanitizeArray(array $data, array $allowHtmlFields = []): array
    {
        $allowedTags = '<p><br><strong><b><em><i><ul><ol><li><a><h1><h2><h3><h4><h5><h6><blockquote><img><div><span><table><thead><tbody><tr><th><td><hr>';

        if (class_exists('\\HTMLPurifier')) {
            $config = \HTMLPurifier_Config::createDefault();
            $config->set('HTML.AllowedAttributes', 'a.href,a.target');
            $purifier = new \HTMLPurifier($config);
            foreach ($data as $key => $value) {
                if (!is_string($value)) continue;
                if (in_array($key, $allowHtmlFields)) {
                    $data[$key] = $purifier->purify($value);
                } else {
                    $data[$key] = strip_tags($value);
                }
            }
            return $data;
        }

        // Fallback
        foreach ($data as $key => $value) {
            if (!is_string($value)) continue;
            if (in_array($key, $allowHtmlFields)) {
                $data[$key] = strip_tags($value, $allowedTags);
            } else {
                $data[$key] = strip_tags($value);
            }
        }

        return $data;
    }
}
