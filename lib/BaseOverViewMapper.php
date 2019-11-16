<?php


namespace Mail;


class BaseOverViewMapper extends MapperInterface
{

    protected function onMap($item)
    {
        return [
            'subject' => $this->parseString($item->subject),
            'from'    => $this->parseMail($item->from, true),
            'to'      => $this->parseMail($item->to),
            'date'    => new \DateTime($item->date),
            'id'      => $item->message_id,
            'uid'     => $item->uid,
            'size'    => $item->size,
            'deleted' => $item->deleted,
            'seen'    => $item->seen
        ];
    }

    protected function parseString(string $string): string
    {
        $parsed = imap_mime_header_decode($string);
        return implode('', array_column($parsed, 'text'));
    }

    protected function parseMail(string $mail, $one = false): array
    {
        $parsed = mailparse_rfc822_parse_addresses($mail);

        if ($one) {
            return [
                'name'    => $this->parseString($parsed[0]['display']),
                'address' => $this->parseString($parsed[0]['address'])
            ];
        } else {
            return array_map(function ($item) {
                return [
                    'name'    => $this->parseString($item['display']),
                    'address' => $this->parseString($item['address'])
                ];
            }, $parsed);
        }
    }
}