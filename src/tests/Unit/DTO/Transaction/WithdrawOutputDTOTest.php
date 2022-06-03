<?php

declare(strict_types=1);

namespace Tests\Unit\DTO\Transaction;

use App\DTO\Transaction\WithdrawOutputDTO;
use PHPUnit\Framework\TestCase;

class WithdrawOutputDTOTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreatesWithdrawOutputFromArray(): void
    {
        $arrayInput = [
            'account_id' => 1,
            'balance' => 200
        ];

        $expectedArrayOutput = [
            'origin' => [
                'id' => '1',
                'balance' => 200
            ]
        ];

        $output = WithdrawOutputDTO::fromArray($arrayInput);

        $this->assertInstanceOf(WithdrawOutputDTO::class, $output);
        $this->assertEquals($expectedArrayOutput, $output->toArray());
    }
}
