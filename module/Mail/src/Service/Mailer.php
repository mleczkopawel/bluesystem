<?php

namespace Mail\Service;

use Mail\Entity\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Message;
use Zend\Mail\Transport\SmtpOptions;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class Mailer
{
    /**
     * @var array
     */
    protected $templateMap;

    /**
     * @var array
     */
    protected $smtp;

    public function __construct($templateMap, $smtp)
    {
        $this->templateMap = $templateMap;
        $this->smtp = $smtp;
    }

    public function send(Mail $mail)
    {
        try {
            $body = $this->renderView($mail);

            $message = new Message();
            $message->setEncoding('UTF-8');
            $message->addFrom($mail->getSender());
            $message->addTo($mail->getRecipients()[0], $mail->getRecipients()[1]);
            $message->setSubject($mail->getTitle());
            $message->setBody($body);
            $message->getHeaders()->removeHeader('Content-Type');
            $message->getHeaders()->addHeaderLine('Content-Type', 'text/html; charset=UTF-8');

            $options = new SmtpOptions($this->smtp);

            $transport = new SmtpTransport();
            $transport->setOptions($options);

            $transport->send($message);
        } catch (\RuntimeException $e) {
            return $e->getMessage();
        }

        return false;
    }

    protected function renderView(Mail $mail)
    {
        $params = (array) $mail->getParams();

        $resolver = new TemplateMapResolver();
        $resolver->setMap($this->templateMap);

        $renderer = new PhpRenderer();
        $renderer->setResolver($resolver);

        $viewModel = new ViewModel();
        $viewModel->setTemplate($mail->getTemplate())
            ->setVariables($params);

        $content = $renderer->render($viewModel);

        if ($mail->getLayout()) {
            $viewLayout = new ViewModel();

            $variables = array_merge(
                array('content' => $content),
                $params
            );

            $viewLayout->setTemplate($mail->getLayout())
                ->setVariables($variables);

            $content = $renderer->render($viewLayout);
        }

        $html = new MimePart($content);
        $html->type = 'text/html';

        $body = new MimeMessage();
        $body->setParts([$html]);

        return $body;
    }
}