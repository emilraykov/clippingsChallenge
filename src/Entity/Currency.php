<?php

namespace Entity;

class Currency
{
    const SUPPORTED_CURRENCIES = [
        'EUR',
        'USD',
        'GBP'
    ];

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $rate;

    public function __construct(
        $name,
        $rate
    ) {
        $this->name = $name;
        $this->rate = $rate;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }
}
