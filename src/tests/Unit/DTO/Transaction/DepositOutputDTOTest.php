<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Transaction;

use App\DTO\Transaction\DepositOutputDTO;
use PHPUnit\Framework\TestCase;

class DepositOutputDTOTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreatesDepositOutputFromArray(): void
    {
        $arrayInput = [
          'account_id' => 1,
          'balance' => 200
        ];

        $expectedArrayOutput = [
            'destination' => [
                'id' => '1',
                'balance' => 200
            ]
        ];

        $output = DepositOutputDTO::fromArray($arrayInput);

        $this->assertInstanceOf(DepositOutputDTO::class, $output);
        $this->assertEquals($expectedArrayOutput, $output->toArray());
    }
}
