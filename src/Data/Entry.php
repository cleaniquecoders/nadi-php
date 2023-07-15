<?php

namespace CleaniqueCoders\Nadi\Data;

use CleaniqueCoders\Nadi\Exceptions\TypeException;
use CleaniqueCoders\Nadi\Metric\Contract;
use CleaniqueCoders\Nadi\Metric\Metric;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class Entry
{
    /**
     * The entry's UUID.
     *
     * @var string
     */
    public $uuid;

    /**
     * The entry's title.
     *
     * @var string
     */
    public $title;

    /**
     * The entry's Description.
     *
     * @var string
     */
    public $description;

    /**
     * The entry's type.
     *
     * @var string
     */
    public $type;

    /**
     * The entry's family hash.
     *
     * @var string|null
     */
    public $hashFamily;

    /**
     * The currently request metric.
     *
     * @var \CleaniqueCoders\Nadi\Metric\Metric
     */
    public $metric;

    /**
     * The entry's content.
     *
     * @var array
     */
    public $content = [];

    /**
     * The entry's tags.
     *
     * @var array
     */
    public $tags = [];

    /**
     * The DateTime that indicates when the entry was recorded.
     *
     * @var \DateTimeInterface
     */
    public $recorded_at;

    /**
     * Create a new incoming entry instance.
     *
     * @param  string|null  $uuid
     * @return void
     */
    public function __construct($type, array $content, $uuid = null)
    {
        $this->uuid = $uuid ?: (string) Uuid::uuid4()->toString();

        $this->type = $type;

        $this->recorded_at = new \DateTimeImmutable();

        $this->content = $content;

        $this->metric = new Metric;
    }

    /**
     * Create a new entry instance.
     *
     * @param  mixed  ...$arguments
     * @return static
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

    /**
     * Assign the entry a given type.
     *
     * @return $this
     */
    public function type(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Assign the entry a family hash.
     *
     * @param  null|string  $hashFamily
     * @return $this
     */
    public function setHashFamily($hashFamily)
    {
        $this->hashFamily = $hashFamily;

        return $this;
    }

    /**
     * Merge tags into the entry's existing tags.
     *
     * @return $this
     */
    public function tags(array $tags)
    {
        $this->tags = array_unique(array_merge($this->tags, $tags));

        return $this;
    }

    /**
     * Determine if the incoming entry has a monitored tag.
     *
     * @return bool
     */
    public function hasMonitoredTag()
    {
        return ! empty($this->tags);
    }

    /**
     * Determine if the incoming entry is an exception.
     *
     * @return bool
     */
    public function isException()
    {
        return $this->type === Type::EXCEPTION;
    }

    /**
     * Get the family look-up hash for the incoming entry.
     *
     * @return string|null
     */
    public function getHashFamily()
    {
        return $this->hashFamily;
    }

    public function getType()
    {
        return $this->type;
    }

    public function addMetric(Contract $contract)
    {
        $this->metric->add($contract);

        return $this;
    }

    public function setTitle(string $value): self
    {
        $this->title = $value;

        return $this;
    }

    public function getTitle(): string
    {
        if(empty($this->title)) {
            switch ($this->getType()) {
                case Type::EXCEPTION:
                    $this->title = data_get($this->getContent(), 'message');
                    break;
                case Type::QUERY:
                    $this->title = data_get($this->getContent(), 'sql');
                    break;
                case Type::QUEUE:
                    $this->title = 'Queue Job '.data_get($this->getContent(), 'data.name').' Failed';
                    break;
                case Type::HTTP:
                    $this->title = data_get($this->getContent(), 'title');
                    break;
                case Type::HTTP_CLIENT:
                    $this->title = '';
                    break;
                case Type::NOTIFICATION:
                    $this->title = 'Failed Notification in '.data_get($this->getContent(), 'notification');
                    break;
                case Type::SCHEDULER:
                    $this->title = '';
                    break;
                case Type::COMMAND:
                    $this->title = '';
                    break;
                case Type::GATE:
                    $this->title = '';
                    break;
                case Type::LOG:
                    $this->title = '';
                    break;
                case Type::MAIL :
                    $this->title = '';
                    break;

                default:
                    TypeException::invalid();
                    break;
            }
        }
        return Str::limit($this->title, 250);
    }

    public function setDescription(string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function getDescription(): string
    {
        if(empty($this->description)) {
            switch ($this->getType()) {
                case Type::EXCEPTION:
                    $this->description = data_get($this->getContent(), 'class') . ' thrown in ' . data_get($this->getContent(), 'file') . ' at line ' . data_get($this->getContent(), 'line') . '.';
                    break;
                case Type::QUERY:
                    $this->description = 'Slow query detected for ' . data_get($this->getContent(), 'connection') . ' connection. Time duration for the query excecuted is: ' . data_get($this->getContent(), 'time') . '.';
                    break;
                case Type::QUEUE:
                    $this->description = 'Queue Job ' . data_get($this->getContent(), 'data.name') . ' failed after ' . data_get($this->getContent(), 'data.tries') . ' tries.';
                    break;
                case Type::HTTP:
                    $this->description = data_get($this->getContent(), 'description');
                    break;
                case Type::HTTP_CLIENT:
                    $this->description = '';
                    break;
                case Type::NOTIFICATION:
                    $this->description = 'Failed notification for ' . data_get($this->getContent(), 'notifiable');
                    break;
                case Type::SCHEDULER:
                    $this->description = '';
                    break;
                case Type::COMMAND:
                    $this->description = '';
                    break;
                case Type::GATE:
                    $this->description = '';
                    break;
                case Type::LOG:
                    $this->description = '';
                    break;
                case Type::MAIL :
                    $this->description = '';
                    break;

                default:
                    TypeException::invalid();
                    break;
            }
        }
        return $this->description;
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get an array representation of the entry for storage.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'hash_family' => $this->getHashFamily(),
            'type' => $this->getType(),
            'content' => $this->getContent(),
            'meta' => $this->metric->toArray(),
            'created_at' => $this->recorded_at->format('Y-m-d H:i:s'),
        ];
    }
}
