<?php


namespace Mail;


class QueryBuilder
{
    const DATE_FORMAT = 'd-M-Y';

    protected $statements = [];

    /**
     * @var Imap
     */
    protected $imap;
    /**
     * @var string
     */
    private $method;

    public function __construct(Imap $imap, string $method)
    {
        $this->imap = $imap;
        $this->method = $method;
    }

    public function get()
    {
        return $this->imap->{$this->method}($this);
    }

    public function getQuery(): string
    {
        $statements = [];
        foreach ($this->statements as $key => $value) {
            if (is_string($key)) {
                $statements[] = "$key \"$value\"";
            } else {
                $statements[] = $value;
            }
        }

        return implode(' ', $statements);
    }

    public function fromDate(\DateTime $dateTime) {
        $this->statements['SINCE'] = $dateTime->format(self::DATE_FORMAT);
        return $this;
    }

    public function old() {
        $this->statements[] = 'OLD';
        return $this;
    }

    public function tillDate(\DateTime $dateTime) {
        $this->statements['BEFORE'] = $dateTime->format(self::DATE_FORMAT);
        return $this;
    }

    public function keyword(string $keyword) {
        $this->statements['KEYWORD'] = $keyword;
        return $this;
    }

    public function from(string $from) {
        $this->statements['FROM'] = $from;
        return $this;
    }

    public function to(string $to) {
        $this->statements['TO'] = $to;
        return $this;
    }

}