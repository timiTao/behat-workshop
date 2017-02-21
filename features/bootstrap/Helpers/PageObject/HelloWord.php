<?php
namespace Helpers\PageObject;

class HelloWord extends AbstractPageObject
{
    const URL = '/hello_word.html';

    public function loadPage()
    {
        $this->getClient()->sendRequest(
            self::URL,
            'GET'
        );
        $this->response = $this->getClient()->getResponse();
    }

    public function hasText(string $text)
    {
        return (bool)strpos($this->response->getBody(), $text);
    }

    public function isValid(): bool
    {
        return $this->getClient()->getStatusCode() == 200;
    }
}