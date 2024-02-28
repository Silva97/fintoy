<?php

namespace Tests\Unit\Rules;

use App\Rules\MaxUserBalanceValue;
use PHPUnit\Framework\TestCase;

class MaxUserBalanceValueTest extends TestCase
{
    public function test_check_value_less_than_current_balance_expects_true()
    {
        $rule = new MaxUserBalanceValue(9001);

        $this->assertTrue($rule->passes('value', 9000));
    }

    public function test_check_value_equals_to_current_balance_expects_true()
    {
        $rule = new MaxUserBalanceValue(9001);

        $this->assertTrue($rule->passes('value', 9001));
    }

    public function test_check_value_bigger_than_current_balance_expects_false()
    {
        $rule = new MaxUserBalanceValue(9001);

        $this->assertFalse($rule->passes('value', 9002));
    }

    public function test_check_non_integer_value_expects_false()
    {
        $rule = new MaxUserBalanceValue(9001);

        $this->assertFalse($rule->passes('value', '7'));
    }
}
