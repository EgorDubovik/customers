@if (Auth::user()->company->settings->referral_active)
      <div class="card-footer">
         <p>Referral stat: <span class="text-success">{{ count($customer->referralStat) }}/{{ $referral_count }} -> ${{ $referral_discount }}</span></p>
      </div>
@endif