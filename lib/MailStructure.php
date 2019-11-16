<?php


namespace Mail;


class MailStructure implements \JsonSerializable
{
    private $structure;

    public function __construct($structure)
    {
        $this->structure = $structure;
    }

    public function getStructure()
    {
        return $this->structure;
    }

    public function attachment(): array
    {
        $attachments = [];

        foreach ($this->structure->parst as $part) {
            if ($part->ifparameters) {
                foreach ($part->parameters as $k => $parameter) if (strtolower($parameter->attribute) === 'filename') {
                    $attachments[] = [
                        'name' => $parameter->value,
                        'encoding' => $part->encoding,
                        'type' => $part->type,
                        'bytes' => $part->bytes,
                        'part'   => $k + 1
                    ];
                }
            }
        }

        return $attachments;
    }

    public function plain(): ?array
    {
        $plain = null;

        foreach ($this->structure->parts as $k => $part) {
            if (strtolower($part->subtype) === 'plain') {

                $plain = [
                    'part' => $k + 1,
                    'encoding' => $part->encoding,
                    'type' => $part->type,
                    'bytes' => $part->bytes,
                    'lines' => $part->lines
                ];

                foreach ($part->parameters as $parameter) if (strtolower($parameter->attribute) === 'charset') {
                    $plain['charset'] = $parameter->value;
                }
            }
        }

        return $plain;
    }

    public function html(): ?array
    {
        $plain = null;

        foreach ($this->structure->parst as $k => $part) {
            if (strtolower($part->subtype) === 'html') {

                $plain = [
                    'part' => $k + 1,
                    'encoding' => $part->encoding,
                    'type' => $part->type,
                    'bytes' => $part->bytes,
                    'lines' => $part->lines
                ];

                foreach ($part->parameters as $parameter) if (strtolower($parameter->attribute) === 'charset') {
                    $plain['charset'] = $parameter->value;
                }
            }
        }

        return $plain;
    }


    public function jsonSerialize()
    {
        return (array)$this->structure;
    }
}