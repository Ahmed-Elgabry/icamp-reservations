<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSiteAndCustomerService extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_site',
        'workername_ar',
        'workername_en',
        'workerphone',
    ];

    /**
     * Get the latest service site data for WhatsApp templates
     */
    public static function getLatestForWhatsApp()
    {
        $data = self::latest()->first();
        
        if (!$data) {
            return null;
        }

        return [
            'service_site' => self::convertHtmlToWhatsApp($data->service_site),
            'workername_ar' => self::convertHtmlToWhatsApp($data->workername_ar),
            'workername_en' => self::convertHtmlToWhatsApp($data->workername_en),
            'workerphone' => $data->workerphone,
        ];
    }

    /**
     * Convert HTML content to WhatsApp formatting
     */
    private static function convertHtmlToWhatsApp($htmlContent)
    {
        if (!$htmlContent) {
            return '';
        }

        // Convert HTML tags to WhatsApp formatting
        $content = str_replace(["<b>", "</b>"], "*", $htmlContent); // Bold
        $content = str_replace(["<strong>", "</strong>"], "*", $content); // Strong (bold)
        $content = str_replace(["<i>", "</i>"], "_", $content); // Italic
        $content = str_replace(["<em>", "</em>"], "_", $content); // Emphasis (italic)
        $content = str_replace(["<u>", "</u>"], "", $content); // Underline (not supported in WhatsApp)
        $content = str_replace(["<s>", "</s>"], "~", $content); // Strikethrough
        $content = str_replace(["<strike>", "</strike>"], "~", $content); // Strikethrough alternative
        $content = str_replace(["<del>", "</del>"], "~", $content); // Delete (strikethrough)
        
        // Handle HTML entities from CKEditor first
        $content = str_replace("&nbsp;", " ", $content); // Non-breaking spaces
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Decode all HTML entities
        
        // Handle paragraphs and line breaks
        $content = str_replace(["<p>", "</p>"], ["", "\n"], $content); // Paragraphs
        $content = str_replace("<br>", "\n", $content); // Line breaks
        $content = str_replace("<br/>", "\n", $content); // Self-closing line breaks
        $content = str_replace("<br />", "\n", $content); // Self-closing line breaks with space
        
        // Remove any remaining HTML tags
        $content = strip_tags($content);
        
        // Clean up extra whitespace
        $content = preg_replace('/\n\s*\n/', "\n\n", $content); // Replace multiple newlines with double newline
        $content = trim($content);
        
        return $content;
    }
}
