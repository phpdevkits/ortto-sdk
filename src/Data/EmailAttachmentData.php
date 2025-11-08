<?php

declare(strict_types=1);

namespace PhpDevKits\Ortto\Data;

use Illuminate\Contracts\Support\Arrayable;

/**
 * Represents an email attachment with base64-encoded content.
 *
 * @implements Arrayable<string, mixed>
 */
class EmailAttachmentData implements Arrayable
{
    /**
     * @param  string  $filename  Name of the file to be attached
     * @param  string  $content  Base64-encoded file content
     * @param  string  $mimeType  MIME type of the attachment (e.g., application/pdf, image/png)
     */
    public function __construct(
        public readonly string $filename,
        public readonly string $content,
        public readonly string $mimeType,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'filename' => $this->filename,
            'content' => $this->content,
            'mime_type' => $this->mimeType,
        ];
    }
}
