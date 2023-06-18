<?php

namespace App\Models;

use App\Nayra\Bpmn\BaseTrait;
use App\Nayra\Contracts\Bpmn\FormalExpressionInterface;

/**
 * FormalExpression
 *
 * @package app\Model
 */
final class FormalExpression implements FormalExpressionInterface
{

    use BaseTrait;

    /**
     * Get the body of the Expression.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->getProperty(FormalExpressionInterface::BPMN_PROPERTY_BODY);
    }

    /**
     * Get the type that this Expression returns when evaluated.
     *
     * @return string
     */
    public function getEvaluatesToType()
    {
        return $this->getProperty(FormalExpressionInterface::BPMN_PROPERTY_EVALUATES_TO_TYPE_REF);
    }

    /**
     * Get the expression language.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->getProperty(FormalExpressionInterface::BPMN_PROPERTY_LANGUAGE);
    }

    /**
     * Invoke the format expression.
     *
     *
     * @return string
     */
    public function __invoke(mixed $data)
    {
        extract($data);
        return eval('return ' . $this->getBody() . ';');
    }
}
