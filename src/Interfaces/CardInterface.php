<?php

namespace FaithPromise\Shared\Interfaces;

interface CardInterface {

    public function getCardTitleAttribute();
    public function getCardSubtitleAttribute();
    public function getCardTextAttribute();
    public function getCardImageAttribute();
    public function getCardUrlAttribute();
    public function getCardUrlTextAttribute();

}