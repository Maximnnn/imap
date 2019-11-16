<?php
declare(strict_types=1);

namespace Mail;

class MailBoxGenerator
{
    protected $port;
    protected $server;
    protected $protocol;
    protected $ssl = 'ssl';
    protected $box = '';

    const PROTOCOL_IMAP = 'imap';
    const PROTOCOL_POP3 = 'pop3';

    public function setPort(int $port)
    {
        $this->port = $port;
    }

    public function setServer(string $server)
    {
        $this->server = $server;
    }

    public function setProtocol(string $protocol)
    {
        $this->protocol = $protocol;
    }

    public function getMailBox(): string
    {
        $suffix = array_filter([
            $this->protocol,
            $this->ssl
        ]);

        $suffix = !empty($suffix) ? '/' . implode('/', $suffix) : '';

        return sprintf('{%s:%s%s}%s', $this->server, $this->port, $suffix, $this->box);
    }

    public function setBox(string $box)
    {
        $this->box = $box;
    }
}