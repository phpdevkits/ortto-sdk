<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Represents inline email asset definition for transactional emails.
 *
 * @implements Arrayable<string, mixed>
 */
class EmailAssetData implements Arrayable
{
    /**
     * @param  string  $fromEmail  Sender email address
     * @param  string  $fromName  Display name for sender
     * @param  string  $subject  Email subject line
     * @param  string  $emailName  Identifier for filtering and reporting
     * @param  string  $htmlBody  Full HTML email content
     * @param  string|null  $replyTo  Reply-to email address
     * @param  array<int, string>|null  $cc  Carbon copy addresses (max 5)
     * @param  array<int, EmailAttachmentData|array<string, mixed>>|null  $attachments  Up to 5 base64-encoded files
     * @param  bool|null  $liquidSyntaxEnabled  Enable Liquid templating (default: true)
     * @param  bool|null  $noClickTracks  Disable URL rewriting for click tracking (default: false)
     * @param  bool|null  $noOpenTracks  Disable tracking pixel (default: false)
     */
    public function __construct(
        public readonly string $fromEmail,
        public readonly string $fromName,
        public readonly string $subject,
        public readonly string $emailName,
        public readonly string $htmlBody,
        public readonly ?string $replyTo = null,
        public readonly ?array $cc = null,
        public readonly ?array $attachments = null,
        public readonly ?bool $liquidSyntaxEnabled = null,
        public readonly ?bool $noClickTracks = null,
        public readonly ?bool $noOpenTracks = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'from_email' => $this->fromEmail,
            'from_name' => $this->fromName,
            'subject' => $this->subject,
            'email_name' => $this->emailName,
            'html_body' => $this->htmlBody,
        ];

        if ($this->replyTo !== null) {
            $data['reply_to'] = $this->replyTo;
        }

        if ($this->cc !== null) {
            $data['cc'] = $this->cc;
        }

        if ($this->attachments !== null) {
            $data['attachments'] = array_map(
                fn (EmailAttachmentData|array $attachment): array => $attachment instanceof EmailAttachmentData ? $attachment->toArray() : $attachment,
                $this->attachments
            );
        }

        if ($this->liquidSyntaxEnabled !== null) {
            $data['liquid_syntax_enabled'] = $this->liquidSyntaxEnabled;
        }

        if ($this->noClickTracks !== null) {
            $data['no_click_tracks'] = $this->noClickTracks;
        }

        if ($this->noOpenTracks !== null) {
            $data['no_open_tracks'] = $this->noOpenTracks;
        }

        return $data;
    }
}
