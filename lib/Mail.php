<?php


namespace Mail;


class Mail
{
    /**
     * @var MailStructure
     */
    private $structure;
    /**
     * @var Imap
     */
    private $imap;
    /**
     * @var int
     */
    private $uid;

    public function __construct(MailStructure $structure, Imap $imap, int $uid)
    {
        $this->structure = $structure;
        $this->imap = $imap;
        $this->uid = $uid;
    }

    public function getPlain(): ?string
    {
        $plain = $this->structure->plain();

        return $plain ? $this->imap->getBody($this->uid, $plain['part']) : null;
    }

    public function getHtml(): ?string
    {
        $html = $this->structure->plain();

        return $html ? $this->imap->getBody($this->uid, $html['part']) : null;
    }

    public function structure(): MailStructure
    {
        return $this->structure;
    }


}