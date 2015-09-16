<?php
namespace Werkint\Bundle\LogBundle\Entity;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableMethods;
use Werkint\Bundle\LogBundle\Service\Logger\LoggableObjectInterface;

/**
 * Сущность для хранения лога в базе
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 *
 * @ORM\Entity(repositoryClass="LogRepository")
 * @ORM\Table(name="werkint_log_log")
 */
class Log
{
    use TimestampableMethods;

    /**
     * @Serializer\Exclude()
     */
    protected $createdAt;
    /**
     * @Serializer\Exclude()
     */
    protected $updatedAt;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    /**
     * Время добавления в лог
     *
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Serializer\Groups("=read")
     **/
    protected $loggedAt;
    /**
     * Сообщение в логе
     *
     * @var string
     * @ORM\Column(type="text")
     * @Serializer\Groups("=read")
     **/
    protected $message;
    /**
     * Уровень ошибки
     *
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Serializer\Groups("=read")
     **/
    protected $level;
    /**
     * Дополнительные данные
     *
     * @var array
     * @ORM\Column(type="json_array")
     * @Serializer\Groups("=read")
     **/
    protected $extraData;
    /**
     * Объект, к которому привязан лог
     *
     * @var string|null
     * @ORM\Column(type="string", name="object_id", nullable=true)
     * @Serializer\Groups("=false")
     **/
    protected $objectId;
    /**
     * Объект, к которому привязан лог
     *
     * @var string|null
     * @ORM\Column(type="string", name="object_class", nullable=true)
     * @Serializer\Groups("=false")
     **/
    protected $objectClass;

    /**
     * @param LoggableObjectInterface|null $object
     * @return $this
     */
    public function setObject(LoggableObjectInterface $object = null)
    {
        $this->objectId = $object ? $object->getId() : null;
        $this->objectClass = $object ? ClassUtils::getClass($object) : null;
        return $this;
    }

    // -- Accessors ---------------------------------------

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getLoggedAt()
    {
        return $this->loggedAt;
    }

    /**
     * @param \DateTime $loggedAt
     * @return $this
     */
    public function setLoggedAt(\DateTime $loggedAt)
    {
        $this->loggedAt = $loggedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param string $level
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtraData()
    {
        return $this->extraData;
    }

    /**
     * @param array $extraData
     * @return $this
     */
    public function setExtraData(array $extraData)
    {
        $this->extraData = $extraData;
        return $this;
    }
}
