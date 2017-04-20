<?php

namespace AppBundle\Builder;

use Swift_Message as SwiftMessage;
use Twig_Environment as TwigEnvironment;

class EmailBuilder
{
    /**
     * @var SwiftMessage
     */
    protected $message;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $senderName;

    /**
     * @var array
     */
    protected $templateVariables = array();

    /**
     * EmailBuilder constructor.
     *
     * @param TwigEnvironment $twig
     * @param $sendFrom
     */
    public function __construct(TwigEnvironment $twig, $sendFrom, $senderName)
    {
        $this->message = SwiftMessage::newInstance();
        $this->twig = $twig;
        $this->sendFrom = $sendFrom;
        $this->senderName = $senderName;
    }

    /**
     * @param $to
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->message->setTo($to);

        return $this;
    }

    /**
     * @param $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param $templateVariables
     *
     * @return $this
     */
    public function setTemplateVariables($templateVariables)
    {
        $this->templateVariables = $templateVariables;

        return $this;
    }

    /**
     * @param $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->message->setSubject($subject);

        return $this;
    }

    /**
     * @return \Swift_Mime_MimePart
     */
    public function build()
    {
        $html = $this->twig->render(
            $this->template,
            $this->templateVariables
        );

        return $this
            ->message
            ->setFrom($this->sendFrom, $this->senderName)
            ->setBody($html, 'text/html');
    }
}