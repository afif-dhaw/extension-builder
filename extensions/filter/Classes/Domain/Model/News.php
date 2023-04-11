<?php

namespace Filter\Filter\Domain\Model;

/**
 * News
 */
class News extends \GeorgRinger\News\Domain\Model\News
{

    /**
     * @var string
     */
    protected $introText;

    /**
     * @return string
     */
    public function getintroText()
    {
        return $this->introText;
    }
    /**
     * @var string
     */
    protected $titleText;

    /**
     * @return string
     */
    public function gettitleText()
    {
        return $this->titleText;
    }

    /**
     * @param string $IntroText
     */
    public function setintroText($introText)
    {
        $this->introText = $introText;
    }
    /**
     * @param string $titleText
     */
    public function settitleText($titleText)
    {
        $this->titleText = $titleText;
    }
}
