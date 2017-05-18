<?php

use PHPUnit\Framework\Assert;

class HelloWordFeatureContext implements \Behat\Behat\Context\Context
{
    /** @var  \Helpers\PageObject\HelloWord */
    private $helloWord;

    public function __construct(\Helpers\PageObject\HelloWord $helloWord)
    {
        $this->helloWord = $helloWord;
    }

    /**
     * @When I show welcome page
     */
    public function iShowWelcomePage()
    {
        $this->helloWord->loadPage();
    }

    /**
     * @Then I should see :text
     */
    public function iShouldSee($text)
    {
        Assert::assertTrue($this->helloWord->hasText($text), $this->helloWord->printRequest());
    }
}