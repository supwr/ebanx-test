<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Transaction;

use App\DTO\Transaction\TransferOutputDTO;
use PHPUnit\Framework\TestCase;

class TransferOutputDTOTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreatesTransferOutputFromArray(): void
    {
        $arrayInput = [
            'origin_account_id' => 100,
            'origin_account_balance' => 30,
            'destination_account_id' => 300,
            'destination_account_balance' => 50,
        ];

        $expectedArrayOutput = [
            'origin' => [
                'id' => '100',
                'balance' => 30
            ],
            'destination' => [
                'id' => '300',
                'balance' => 50
            ]
        ];

        $output = TransferOutputDTO::fromArray($arrayInput);

        $this->assertInstanceOf(TransferOutputDTO::class, $output);
        $this->assertEquals($expectedArrayOutput, $output->toArray());
    }
}
