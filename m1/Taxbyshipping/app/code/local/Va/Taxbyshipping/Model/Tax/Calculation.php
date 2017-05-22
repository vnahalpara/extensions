<?php
class Va_Taxbyshipping_Model_Tax_Calculation extends Mage_Tax_Model_Resource_Calculation
{
	public function getCalculationProcess($request, $rates = null)
    {
    	$shipping = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getShippingMethod();
    	$configValue = Mage::getStoreConfig('taxbyshipping/group/methods');
    	$array =  explode(',',$configValue);
    	if(in_array($shipping,$array))
    	{
    		return 0;
    	}
        if (is_null($rates)) {
            $rates = $this->_getRates($request);
        }

        $result = array();
        $row = array();
        $ids = array();
        $currentRate = 0;
        $totalPercent = 0;
        $countedRates = count($rates);
        for ($i = 0; $i < $countedRates; $i++) {
            $rate = $rates[$i];
            $value = (isset($rate['value']) ? $rate['value'] : $rate['percent']) * 1;

            $oneRate = array(
                'code' => $rate['code'],
                'title' => $rate['title'],
                'percent' => $value,
                'position' => $rate['position'],
                'priority' => $rate['priority'],
            );
            if (isset($rate['tax_calculation_rule_id'])) {
                $oneRate['rule_id'] = $rate['tax_calculation_rule_id'];
            }

            if (isset($rate['hidden'])) {
                $row['hidden'] = $rate['hidden'];
            }

            if (isset($rate['amount'])) {
                $row['amount'] = $rate['amount'];
            }

            if (isset($rate['base_amount'])) {
                $row['base_amount'] = $rate['base_amount'];
            }
            if (isset($rate['base_real_amount'])) {
                $row['base_real_amount'] = $rate['base_real_amount'];
            }
            $row['rates'][] = $oneRate;

            if (isset($rates[$i + 1]['tax_calculation_rule_id'])) {
                $rule = $rate['tax_calculation_rule_id'];
            }
            $priority = $rate['priority'];
            $ids[] = $rate['code'];

            if (isset($rates[$i + 1]['tax_calculation_rule_id'])) {
                while (isset($rates[$i + 1]) && $rates[$i + 1]['tax_calculation_rule_id'] == $rule) {
                    $i++;
                }
            }

            $currentRate += $value;

            if (!isset($rates[$i + 1]) || $rates[$i + 1]['priority'] != $priority
                || (isset($rates[$i + 1]['process']) && $rates[$i + 1]['process'] != $rate['process'])
            ) {
                if (!empty($rates[$i]['calculate_subtotal'])) {
                    $row['percent'] = $currentRate;
                    $totalPercent += $currentRate;
                } else {
                    $row['percent'] = $this->_collectPercent($totalPercent, $currentRate);
                    $totalPercent += $row['percent'];
                }
                $row['id'] = implode($ids);
                $result[] = $row;
                $row = array();
                $ids = array();

                $currentRate = 0;
            }
        }
        return $result;
    }
}
?>