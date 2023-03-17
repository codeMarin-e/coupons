<?php

    namespace App\Http\Requests\Admin;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Support\Arr;
    use App\Models\Coupon;
    use App\Models\Discount;

    class CouponRequest extends FormRequest
    {

        private $mergeReturn = [];

        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize()
        {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules()
        {
            $rules  = [
                'add.name' => 'required|max:255',
                'code' => 'required|max:255',
                'min_total' => 'numeric',
                'max_total' => 'numeric',
                'max_orders' => 'numeric',
                'free_taxes' => 'boolean',
                'discount_value' => ['numeric'],
                'discount_type' => [ 'required', function($attribute, $value, $fail) {
                    if(!in_array($value, Discount::$types))
                        return $fail(trans("admin/coupons/validation.discount.type.required"));
                } ],
            ];

            $rules = array_merge(
                $rules,
                \App\Http\Requests\Admin\ActiviableRequest::validation_rules(),
            );

            // @HOOK_REQUEST_RULES

            return $rules;
        }

        public function messages() {
            $return = Arr::dot((array)trans('admin/coupons/validation'));

            // @HOOK_REQUEST_MESSAGES

            return $return;
        }

        public function validationData() {
            $inputBag = 'coupon';
            $this->errorBag = $inputBag; //do not where else
            $inputs = $this->all();
            $inputs[$inputBag]['free_taxes'] = isset($inputs[$inputBag]['free_taxes']);
            $inputs[$inputBag]['max_orders'] = isset($inputs[$inputBag]['max_orders'])? (float)$inputs[$inputBag]['max_orders'] : 0;

            $inputs[$inputBag]['min_total'] = (float)str_replace(',', '.', $inputs[$inputBag]['min_total']?? 0);
            $inputs[$inputBag]['max_total'] = (float)str_replace(',', '.', $inputs[$inputBag]['max_total']?? 0);
            $inputs[$inputBag]['discount_value'] = (float)str_replace(',', '.', $inputs[$inputBag]['discount_value']?? 0);
            \App\Http\Requests\Admin\ActiviableRequest::validation_prework($inputs);

            // @HOOK_REQUEST_PREPARE

            $this->replace($inputs);
            request()->replace($inputs); //global request should be replaced, too
            return $inputs[$inputBag];
        }

        public function validated($key = null, $default = null) {
            $validatedData = parent::validated($key, $default);

            // @HOOK_REQUEST_VALIDATED

            if(is_null($key)) {
                \App\Http\Requests\Admin\ActiviableRequest::validateData($validatedData);

                // @HOOK_REQUEST_AFTER_VALIDATED

                return array_merge($validatedData, $this->mergeReturn);
            }

            // @HOOK_REQUEST_AFTER_VALIDATED_KEY

            return $validatedData;
        }
    }
