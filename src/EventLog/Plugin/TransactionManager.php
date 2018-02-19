<?php

declare(strict_types=1);

namespace StephBug\ModelEvent\EventLog\Plugin;

use Prooph\Common\Event\ActionEvent;
use Prooph\ServiceBus\MessageBus;
use Prooph\ServiceBus\Plugin\AbstractPlugin;
use StephBug\ModelEvent\EventLog\TransactionalEventLogger;
use StephBug\ServiceBus\Bus\CommandBus;

class TransactionManager extends AbstractPlugin
{
    /**
     * @var TransactionalEventLogger
     */
    private $eventLog;

    /**
     * TransactionManager constructor.
     *
     * @param TransactionalEventLogger $eventLog
     */
    public function __construct(TransactionalEventLogger $eventLog)
    {
        $this->eventLog = $eventLog;
    }

    public function attachToMessageBus(MessageBus $messageBus): void
    {
        $this->onDispatch($messageBus);

        $this->onFinalize($messageBus);
    }

    private function onDispatch(MessageBus $messageBus): void
    {
        $this->listenerHandlers[] = $messageBus->attach(
            CommandBus::EVENT_DISPATCH,
            function (ActionEvent $actionEvent): void {
                $this->eventLog->beginTransaction();
            },
            CommandBus::PRIORITY_INVOKE_HANDLER + 1000
        );
    }

    private function onFinalize(MessageBus $messageBus): void
    {
        $this->listenerHandlers[] = $messageBus->attach(
            CommandBus::EVENT_FINALIZE,
            function (ActionEvent $actionEvent): void {
                if ($this->eventLog->inTransaction()) {
                    if ($actionEvent->getParam(CommandBus::EVENT_PARAM_EXCEPTION)) {
                        $this->eventLog->rollBack();
                    } else {
                        $this->eventLog->commit();
                    }
                }
            },
            1000
        );
    }
}