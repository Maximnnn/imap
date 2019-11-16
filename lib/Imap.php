<?php
declare(strict_types=1);

namespace Mail;

class Imap
{
    protected $connection = null;

    protected $mappers = [
        'folder' => null,
        'mail'   => null,
        'overView' => null
    ];

    protected $userName;
    /**@var $boxGenerator MailBoxGenerator */
    protected $boxGenerator;

    public function __construct()
    {
        $this->mappers = [
            'folder' => new BaseFolderMapper(),
            'overView' => new BaseOverViewMapper()
        ];
    }

    public function connect(string $userName, string $password, MailBoxGenerator $boxGenerator)
    {
        $this->boxGenerator = $boxGenerator;
        $this->userName = $userName;

        $this->connection = imap_open($boxGenerator->getMailBox(), $userName, $password);
    }

    public function setFolderMapper(MapperInterface $mapper) {
        $this->mappers['folder'] = $mapper;
    }

    public function setMailMapper(MapperInterface $mapper) {
        $this->mappers['mail'] = $mapper;
    }

    public function setOverViewMapper(MapperInterface $mapper) {
        $this->mappers['overView'] = $mapper;
    }

    public function getFolders()
    {
        $result = imap_getmailboxes($this->connection, $this->boxGenerator->getMailBox(), '*');

        return $this->map($result, 'folder');
    }

    public function searchUids(QueryBuilder $queryBuilder = null)
    {
        if (is_null($queryBuilder)) {
            return new QueryBuilder($this, __FUNCTION__);
        }

        return imap_search($this->connection, $queryBuilder->getQuery(), SE_UID);
    }

    public function getOverView(int $fromUid, int $toUid = null)
    {
        $search = $fromUid . (!is_null($toUid) ? ":$toUid" : '');
        return $this->map(imap_fetch_overview($this->connection, $search, FT_UID), 'overView');
    }

    public function getMail(int $uid): Mail
    {
        $structure = new MailStructure(imap_fetchstructure($this->connection, $uid, FT_UID));

        return new Mail($structure, $this, $uid);
    }

    public function getBody(int $uid, $part)
    {
        return imap_fetchbody($this->connection, $uid, (string)$part, FT_UID | FT_PEEK);
    }

    protected function map($result, string $mapper)
    {
        if ($this->mappers[$mapper]) {
            $result = $this->mappers[$mapper]->map($result);
        }
        return $result;
    }

}