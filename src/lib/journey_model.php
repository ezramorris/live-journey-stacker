<?php

class Journey {
    function __construct(
        public DateTimeImmutable $date, 
        public array $legs
    ){}
}

interface Leg {}

class TrainLeg implements Leg {
    function __construct(
        public string $train_uid,
        public string $boarding_crs,
        public string $alighting_crs
    ){}
}

