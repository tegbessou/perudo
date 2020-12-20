<?php

namespace App\Tests\Behat;

use Behat\Mink\Exception\ExpectationException;
use Behat\MinkExtension\Context\MinkContext;

class FrontContext extends MinkContext
{
    use WaitUntilTrait;

    /**
     * @Given /^I click on Material UI select field with label "([^"]+)"$/
     */
    public function clickOnMaterialUiSelectWithLabel(string $label): void
    {
        $this->iClickOnCSSPath('label[for="'.$label.'"] + div');
    }

    /**
     * @Given /^I click on "([^"]+)"$/
     */
    public function iClickOn(string $path): void
    {
        $this->iClickOnCSSPath($path);
    }

    /**
     * @Given /^I choose value "([^"]+)" on Material UI select$/
     */
    public function chooseOnMaterialUiSelect(string $value, string $msg = null): void
    {
        $this->spin(function () use ($value, $msg) {
            $this->waitUntil(20, function () {
                parent::assertElementNotOnPage('.MuiCircularProgress-root');
            });
            $session = $this->getSession();
            $page = $session->getPage();

            // First we try to find the element with CSS: a <li> with a data-value equal to $value (old select).
            $element = $page->find('css', 'li[data-value="'.$value.'"]');

            // Then with xpath: an element with text content equal to $value (new select with autocomplete).
            if (null === $element) {
                $locator = '//li[normalize-space(.)="'.$value.'"]';
                $element = $page->find('xpath', $locator);

                if (null === $element) {
                    $msg = $msg ?: 'Can\'t find "'.$value.'"';
                    throw new ExpectationException($msg, $this->getSession()->getDriver());
                }
            }

            try {
                $element->click();
            } catch (\Behat\Mink\Exception\ElementException $e) {
                // Hack to avoid chrome selenium driver errors when an event is listened on item parent
                if (false !== strpos($e->getMessage(), 'Another element would receive the click')) {
                    $element->getParent()->click();
                } else {
                    throw $e;
                }
            }

            return true;
        });
    }

    /**
     * @Then (I) choose value :value on Material UI select with id :id
     */
    public function openAndChooseInAMaterialUISelect(string $id, string $value, string $msg = null)
    {
        $this->waitUntil(20, function () {
            parent::assertElementNotOnPage('.MuiCircularProgress-root');
        });
        $this->iClickOnCSSPath('label[id="'.$id.'"] + div');

        $this->chooseOnMaterialUiSelect($value, $msg);
    }

    /**
     * Wait for a element to be hidden.
     *
     * @Then (I )wait :seconds second(s) for :element to be hidden
     */
    public function iWaitSecondsForElementToBeHidden(int $seconds, string $element): void
    {
        $this->waitUntil($seconds, function () use ($element) {
            $this->elementShouldBeHidden($element);
        });
    }

    /**
     * element should be hidden.
     *
     * @Then :element should be hidden
     */
    public function elementShouldBeHidden(string $element): void
    {
        if ($this->getSession()->getPage()->findAll('css', $element)) {
            throw new ExpectationException(sprintf('Element %s should be hidden', $element), $this->getSession()->getDriver());
        }
    }

    /**
     * Wait until no request is in progress
     * Fails if some requests are in progress after 60 seconds.
     *
     * Example: Then I wait until there is no request in progress
     *
     * @Then (I )wait until there is no request in progress
     */
    public function iWaitUntilThereIsNoRequestInProgress(): void
    {
        $this->waitUntil(20, function () {
            $js = 'return window.bpm && window.bpm.stats.requests.getRequestInProgress();';
            $requestInProgress = $this->getSession()->evaluateScript($js);
            if ((int) $requestInProgress > 0) { // there are at least 1 XHR in progress
                throw new ExpectationException('There are at least one XHR in progress', $this->getSession()->getDriver());
            }
        });
    }

    public function iClickOnCSSPath(string $source, string $msg = null): void
    {
        $session = $this->getSession();
        $page = $session->getPage();

        $element = $page->find('css', $source);

        if (null === $element) {
            $msg = $msg ?: 'Can\'t find "'.$source.'"';
            throw new ExpectationException($msg, $this->getSession()->getDriver());
        }

        try {
            $element->click();
        } catch (\Behat\Mink\Exception\ElementException $e) {
            // Hack to avoid chrome selenium driver errors when an event is listened on item parent
            if (false !== strpos($e->getMessage(), 'Other element would receive the click')) {
                $element->getParent()->click();
            } else {
                throw $e;
            }
        }
    }

    /**
     * @Given /^I check if "([^"]+)" field is equal to "([^"]*)"$/
     */
    public function iCheckIfFieldIsEqualTo(string $element, string $value): void
    {
        $this->spin(function () use ($element, $value) {
            $this->waitUntil(20, function () {
                parent::assertElementNotOnPage('.MuiAutocomplete-noOptions');
            });

            $session = $this->getSession();
            $page = $session->getPage();
            // check on normal node : <node id="$element" value="$value" /> and <div id="$id-chip-<$index>" data-value="$value" /> for tags
            $node = $page->findAll(
                'css',
                'div[id^='.$element.'] + input[value="'.$value.'"]'
            );
            if (empty($node)) {
                throw new ExpectationException($element.' is not equal to '.$value, $this->getSession()->getDriver());
            }

            return true;
        }, 10);
    }

    public function pressButton($button)
    {
        $this->spin(function () use ($button) {
            parent::pressButton($button);

            return true;
        });

        $this->waitUntil(20, function () {
            parent::assertElementNotOnPage('.MuiCircularProgress-root');
        });

        $this->iWaitUntilThereIsNoRequestInProgress();
    }

    public function spin($lambda, $wait = 5)
    {
        for ($i = 0; $i < $wait; ++$i) {
            try {
                if ($lambda($this)) {
                    return true;
                }
            } catch (Exception $e) {
                // do nothing
            }
            sleep(1);
        }

        $lambda($this);
    }
}
