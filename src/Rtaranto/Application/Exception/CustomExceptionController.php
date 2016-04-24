<?php
namespace Rtaranto\Application\Exception;

use Symfony\Component\HttpKernel\Exception\FlattenException as HttpFlattenException;
use Symfony\Component\Debug\Exception\FlattenException as DebugFlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;

class CustomExceptionController extends \FOS\RestBundle\Controller\ExceptionController
{
    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null)
    {
        /*
         * Validates that the exception that is handled by the Exception controller is either a DebugFlattenException
         * or HttpFlattenException.
         * Type hinting has been removed due to a BC change in symfony/symfony 2.3.5.
         *
         * @see https://github.com/FriendsOfSymfony/FOSRestBundle/pull/565
         */
        if (!$exception instanceof DebugFlattenException && !$exception instanceof HttpFlattenException) {
            throw new InvalidArgumentException(sprintf(
                'ExceptionController::showAction can only accept some exceptions (%s, %s), "%s" given',
                'Symfony\Component\HttpKernel\Exception\FlattenException',
                'Symfony\Component\Debug\Exception\FlattenException',
                get_class($exception)
            ));
        }

        try {
            $format = $this->getFormat($request, $request->getRequestFormat());
        } catch (Exception $e) {
            $format = null;
        }
        if (null === $format) {
            $message = 'No matching accepted Response format could be determined, while handling: ';
            $message .= $this->getExceptionMessage($exception);

            return $this->createPlainResponse($message, Codes::HTTP_NOT_ACCEPTABLE, $exception->getHeaders());
        }

        $currentContent = $this->getAndCleanOutputBuffering($request);
        $code = $this->getStatusCode($exception);
        $viewHandler = $this->container->get('fos_rest.view_handler');
        $parameters = $this->getParameters($viewHandler, $currentContent, $code, $exception, $logger, $format);
        $showException = $request->attributes->get('showException', $this->container->get('kernel')->isDebug());

        try {
            if (!$viewHandler->isFormatTemplating($format)) {
                $parameters = $this->createExceptionWrapper($parameters);
            }
            
            ///The following code is what is actually overrided
            $parametersAsArray = array();
            $parametersAsArray['code'] = $parameters->getCode();
            $parametersAsArray['message'] = $parameters->getMessage();
            if (!empty($parameters->getErrors())) {
                $parametersAsArray['errors'] = $parameters->getErrors();
            }
            ///
            
            $view = View::create($parametersAsArray, $code, $exception->getHeaders());
            $view->setFormat($format);

            if ($viewHandler->isFormatTemplating($format)) {
                $view->setTemplate($this->findTemplate($request, $format, $code, $showException));
            }

            $response = $viewHandler->handle($view);
        } catch (Exception $e) {
            $message = 'An Exception was thrown while handling: ';
            $message .= $this->getExceptionMessage($exception);
            $response = $this->createPlainResponse($message, Codes::HTTP_INTERNAL_SERVER_ERROR, $exception->getHeaders());
        }

        return $response;
    }
}
