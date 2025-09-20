<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessageTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'message_ar',
        'message_en',
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Template types constants
    const TYPE_SHOW_PRICE = 'show_price';
    const TYPE_INVOICE = 'invoice';
    const TYPE_RECEIPT = 'receipt';
    const TYPE_RESERVATION_DATA = 'reservation_data';
    const TYPE_EVALUATION = 'evaluation';
    const TYPE_PAYMENT_LINK_CREATED = 'payment_link_created';
    const TYPE_PAYMENT_LINK_RESEND = 'payment_link_resend';
    const TYPE_BOOKING_REMINDER = 'booking_reminder';
    const TYPE_BOOKING_ENDING_REMINDER = 'booking_ending_reminder';
    const TYPE_MANUAL_TEMPLATE = 'manual_template';

    // Get all available types
    public static function getTypes()
    {
        return [
            self::TYPE_MANUAL_TEMPLATE => __('dashboard.manual_template'),
            self::TYPE_SHOW_PRICE => __('dashboard.show_price'),
            self::TYPE_INVOICE => __('dashboard.invoice'),
            self::TYPE_RECEIPT => __('dashboard.receipt'),
            self::TYPE_RESERVATION_DATA => __('dashboard.reservation_data'),
            self::TYPE_EVALUATION => __('dashboard.evaluation'),
            self::TYPE_PAYMENT_LINK_CREATED => __('dashboard.payment_link_created'),
            self::TYPE_PAYMENT_LINK_RESEND => __('dashboard.payment_link_resend'),
            self::TYPE_BOOKING_REMINDER => __('dashboard.booking_reminder'),
            self::TYPE_BOOKING_ENDING_REMINDER => __('dashboard.booking_ending_reminder'),
        ];
    }

    // Scope for active templates
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for specific type
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Get template by type (first active one)
    public static function getByType($type)
    {
        return self::active()->ofType($type)->first();
    }

    // Replace placeholders in message
    public function getProcessedMessage($language = 'ar', $customerName = '', $evaluationLink = '', $serviceSiteData = null)
    {
        $message = $language === 'en' ? $this->message_en : $this->message_ar;
        
        return $this->processMessageContent($message, $customerName, $evaluationLink, $serviceSiteData);
    }

    // Get bilingual message (Arabic + English)
    public function getBilingualMessage($customerName = '', $evaluationLink = '', $serviceSiteData = null)
    {
        $arabicMessage = $this->processMessageContent($this->message_ar, $customerName, $evaluationLink, $serviceSiteData);
        $englishMessage = $this->processMessageContent($this->message_en, $customerName, $evaluationLink, $serviceSiteData);
        
        // Combine both messages with a separator
        return $arabicMessage . "\n\n" . "━━━━━━━━━━━━━" . "\n\n" . $englishMessage;
    }

    // Helper method to process message content
    private function processMessageContent($message, $customerName = '', $evaluationLink = '', $serviceSiteData = null)
    {
        // Replace placeholders
        $message = str_replace('[اسم العميل]', $customerName, $message);
        $message = str_replace('[Customer Name]', $customerName, $message);
        $message = str_replace('[customer\'s name]', $customerName, $message);
        $message = str_replace('[Customer\'s Name]', $customerName, $message);
        $message = str_replace('[رابط التقييم]', $evaluationLink, $message);
        $message = str_replace('[🔗 رابط التقييم]', $evaluationLink, $message); // Arabic with emoji
        $message = str_replace('[🔗 Evaluation Link]', $evaluationLink, $message);
        $message = str_replace('[Evaluation Link]', $evaluationLink, $message);
        
        // Replace service site placeholders if data is provided
        if ($serviceSiteData) {
            $message = str_replace('[موقع المخيم]', $serviceSiteData['service_site'] ?? '', $message);
            $message = str_replace('[Camp Location]', $serviceSiteData['service_site'] ?? '', $message);
            $message = str_replace('[اسم العامل]', $serviceSiteData['workername_ar'] ?? '', $message);
            $message = str_replace('[Worker Name]', $serviceSiteData['workername_en'] ?? '', $message);
            $message = str_replace('[رقم هاتف العامل]', $serviceSiteData['workerphone'] ?? '', $message);
            $message = str_replace('[Worker Phone]', $serviceSiteData['workerphone'] ?? '', $message);
        }
        
        // Convert HTML tags to WhatsApp formatting
        $message = str_replace(["<b>", "</b>"], "*", $message); // Bold
        $message = str_replace(["<strong>", "</strong>"], "*", $message); // Strong (bold)
        $message = str_replace(["<i>", "</i>"], "_", $message); // Italic
        $message = str_replace(["<em>", "</em>"], "_", $message); // Emphasis (italic)
        $message = str_replace(["<u>", "</u>"], "", $message); // Underline (not supported in WhatsApp)
        $message = str_replace(["<s>", "</s>"], "~", $message); // Strikethrough
        $message = str_replace(["<strike>", "</strike>"], "~", $message); // Strikethrough alternative
        $message = str_replace(["<del>", "</del>"], "~", $message); // Delete (strikethrough)
        
        // Handle HTML entities from CKEditor first
        $message = str_replace("&nbsp;", " ", $message); // Non-breaking spaces
        $message = html_entity_decode($message, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Decode all HTML entities
        
        // Handle paragraphs and line breaks
        $message = str_replace(["<p>", "</p>"], ["", "\n"], $message); // Paragraphs
        $message = str_replace("<br>", "\n", $message); // Line breaks
        $message = str_replace("<br/>", "\n", $message); // Self-closing line breaks
        $message = str_replace("<br />", "\n", $message); // Spaced self-closing line breaks
        
        // Clean up extra spaces at the beginning of lines (from CKEditor &nbsp;)
        $message = preg_replace('/\n\s+/', "\n", $message); // Remove spaces after newlines
        $message = ltrim($message); // Remove leading spaces from the entire message
        
        // Handle lists
        $message = str_replace(["<ul>", "</ul>"], ["", ""], $message); // Unordered lists
        $message = str_replace(["<ol>", "</ol>"], ["", ""], $message); // Ordered lists
        $message = str_replace(["<li>", "</li>"], ["• ", "\n"], $message); // List items
        
        // Handle links (preserve the link text and URL)
        $message = preg_replace('/<a[^>]*href=["\']([^"\']*)["\'][^>]*>([^<]*)<\/a>/', '$2 ($1)', $message);
        
        // Handle headings (convert to bold with line breaks)
        $message = str_replace(["<h1>", "</h1>"], ["*", "*\n"], $message);
        $message = str_replace(["<h2>", "</h2>"], ["*", "*\n"], $message);
        $message = str_replace(["<h3>", "</h3>"], ["*", "*\n"], $message);
        $message = str_replace(["<h4>", "</h4>"], ["*", "*\n"], $message);
        $message = str_replace(["<h5>", "</h5>"], ["*", "*\n"], $message);
        $message = str_replace(["<h6>", "</h6>"], ["*", "*\n"], $message);
        
        // Handle blockquotes
        $message = str_replace(["<blockquote>", "</blockquote>"], ["", ""], $message);
        
        // Handle code (use monospace formatting)
        $message = str_replace(["<code>", "</code>"], "```", $message);
        $message = str_replace(["<pre>", "</pre>"], ["```\n", "\n```"], $message);
        
        // Clean up any remaining HTML tags
        $message = strip_tags($message);
        
        // Clean up extra line breaks
        $message = preg_replace('/\n{3,}/', "\n\n", $message); // Max 2 consecutive line breaks
        $message = trim($message); // Remove leading/trailing whitespace
        
        return $message;
    }
}
