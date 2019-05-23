<?php

namespace App\Services;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class EstimationService
{
    private $wpdb;

    private $tablePrefix;

    public function __construct($wpdb)
    {
        $this->wpdb = $wpdb;
        $this->tablePrefix = $this->wpdb->prefix . 'orders';
    }

    public function validation(array $estimateData): ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection([
            'id' => [
                new Assert\Required(),
            ],
            'date' => [
                new Assert\NotBlank(),
                new Assert\Callback(static function($object, ExecutionContextInterface $context) {
                    $date = \DateTime::createFromFormat('Y-m-d\TH:i:s.u+', $object);
                    if($date === false) {
                        $context
                            ->buildViolation('The estimate date does not math with required format.')
                            ->addViolation();
                    }
                }),
            ],
            'extra' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'numeric']),
            ],
            'formType' => [
                new Assert\NotBlank(),
            ],
            'description' => [
                new Assert\Optional(),
            ],
            'deposit' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'numeric']),
            ],
            'customer' => new Assert\Collection([
                'name' => new Assert\Required(),
                'email' => [
                    new Assert\Required(),
                    new Assert\Email(),
                ],
                'phone' => new Assert\Required(),
                'address' => new Assert\Required(),
            ]),
            'detail' => new Assert\Collection([
                new Assert\Collection([
                    'id' => [
                        new Assert\Required(),
                    ],
                    'amount' => [
                        new Assert\NotBlank(),
                        new Assert\Type(['type' => 'numeric']),
                    ],
                    'description' => [
                        new Assert\NotBlank(),
                    ],
                    'quantity' => [
                        new Assert\NotBlank(),
                        new Assert\Type(['type' => 'numeric']),
                    ],
                    'rate' => [
                        new Assert\NotBlank(),
                        new Assert\Type(['type' => 'numeric']),
                    ],
                ]),
            ])
        ]);

        $validator = Validation::createValidator();
        return $validator->validate($estimateData, $constraint);
    }

    public function toValidate($estimateData): bool
    {
        if($this->validation($estimateData)->count() > 0) {
            throw new \RuntimeException('The data structure is not valid.');
        }
        return true;
    }

    public function add(array $estimationData): int
    {
        $this->toValidate($estimationData);

        $this->wpdb->insert(
            $this->tablePrefix,
            [
                'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
                'detail' => json_encode($estimationData)
            ]
        );

        return $this->wpdb->insert_id;
    }

    public function getEstimateById(int $id): array
    {
        $result = $this->wpdb->get_results($this->wpdb->prepare("SELECT id, created_at, detail FROM {$this->tablePrefix} WHERE id = {$id} ORDER BY id DESC LIMIT 1", null), ARRAY_A);
        if(!$result){
            wp_die('No order was found.');
        }
        $estimation = $result[0];
        $estimation['detail'] = json_decode($estimation['detail'], true);
        return $estimation;
    }

}
