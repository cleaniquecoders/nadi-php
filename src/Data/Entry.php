<?php

namespace CleaniqueCoders\Nadi\Data;

use CleaniqueCoders\Nadi\Metric\Contract;
use CleaniqueCoders\Nadi\Metric\Metric;
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
    public $familyHash;

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
        $this->uuid = $uuid ?: (string) Uuid::uuid7()->toString();

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
     * @param  null|string  $familyHash
     * @return $this
     */
    public function withFamilyHash($familyHash)
    {
        $this->familyHash = $familyHash;

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
    public function familyHash()
    {
        return $this->familyHash;
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

    /**
     * Get an array representation of the entry for storage.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'uuid' => $this->uuid,
            'family_hash' => $this->familyHash(),
            'type' => $this->getType(),
            'content' => $this->content,
            'meta' => $this->metric->toArray(),
            'created_at' => $this->recorded_at->format('Y-m-d H:i:s'),
        ];
    }
}
