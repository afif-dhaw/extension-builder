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
    protected $IntroText;

    /**
     * @return string
     */
    public function getIntroText()
    {
        return $this->IntroText;
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
    public function setIntroText($IntroText)
    {
        $this->IntroText = $IntroText;
    }
    /**
     * @param string $titleText
     */
    public function settitleText($titleText)
    {
        $this->titleText = $titleText;
    }
}
