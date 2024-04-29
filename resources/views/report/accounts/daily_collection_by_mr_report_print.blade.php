
			<table class="payment table table-sm table-bordered mb-5 text-center" style="font-size: small;" id="table1">
				<thead>
					<tr>
						<th width="100px">Date</th>
						<th>AE</th>
						<th colspan="2">Stu ID|Name</th>
						<th>Contact</th>
						<th>Batch</th>
						<th>MR</th>
						<th>Inv</th>
						<th>Type</th>
						<th>Due Date</th>
						<th>Invoice Amt.</th>
						<th>Paid</th>
						<th>Dis</th>
						<th>Due</th>
						<th>Mode</th>

					</tr>
				</thead>
				<tbody>
					@php
					$total_paid_amount = 0;
					$total_dis = 0;
					$total_cpyable = 0;
					@endphp
					@foreach($payments as $p)
					@php
					//echo $p->paymentDetail->count();die;
					$rowCount = \DB::table('paymentdetails')->where('paymentId', $p->paymentId)->count();
					//echo $rowCount;
					@endphp
					@php
					$total_paid_amount += $p->cpaidAmount;
					$total_dis += $p->discount;

					@endphp
					<tr>
						<td rowspan="" class="align-middle">
							<p class="p-0 m-1">{{date('d M Y',strtotime($p->paymentDate))}}</p>
						</td>
						<td rowspan="" class="align-middle">{{$p->username}}</td>
						<td class="align-middle">{{$p->studentId}}</td>
						<td class="align-middle">{{$p->name}}</td>
						<td class="align-middle">
							@if(currentUserId() == $p->executiveId || currentUser() == 'salesmanager' || currentUser() == 'superadmin' || currentUser() == 'operationmanager')
							{{$p->contact}}
							@else
							-
							@endif
						</td>
						<td class="align-middle">
							@if($p->batchId)
							{{$p->batchId}}
							@else
							{{$p->courseName}}
							@endif
						</td>
						<td class="align-middle">{{$p->mrNo}}</td>
						<td class="align-middle">{{--$p->invoiceId--}}
							@if(\DB::table('payments')
							->join('paymentdetails','paymentdetails.paymentId','payments.id')
							->where(['paymentdetails.studentId'=>$p->studentId,'paymentdetails.batchId' => $p->bid])->whereNotNull('payments.invoiceId')->exists() && $p->feeType==2)
							{{
								\DB::table('payments')
							->join('paymentdetails','paymentdetails.paymentId','payments.id')
							->where(['paymentdetails.studentId'=>$p->studentId,'paymentdetails.batchId' => $p->bid])->whereNotNull('payments.invoiceId')->first()->invoiceId}}
							@else
							-
							@endif
						</td>
						@php
						if($p->feeType==1)
						$text = "Registration";
						else
						$text = "Invoice";
						@endphp
						<td class="align-middle">{{$text}}</td>
						@if($p->feeType==1)
							<td>-</td>
							<td>-</td>
							<td>{{$p->cpaidAmount}}</td>
							<td>-</td>
							<td>-</td>
						@else
							@if($p->cpaidAmount+$p->discount == $p->cPayable)
								<td class="align-middle">-</td>
							@else
							<td class="align-middle"><strong class="text-danger" style="font-size:12px;">@if($p->dueDate){{date('d M Y',strtotime($p->dueDate))}} @else - @endif</strong></td>
							@endif
						<td class="align-middle">
						
						</td>
						<td class="align-middle">{{$p->cpaidAmount}}</td>
						<td class="align-middle">{{$p->discount?$p->discount:0}}</td>
						<td class="align-middle">{{($p->cPayable-($p->cpaidAmount+$p->discount))}}</td>
						@php $total_cpyable += ($p->cPayable-($p->cpaidAmount+$p->discount)); @endphp
						@endif
						<td class="align-middle">@if($p->payment_mode == 1) Cash @elseif($p->payment_mode == 2) Bkash @else Bank @endif</td>
						
					</tr>

					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="11"></td>
						<td>{{$total_paid_amount}}</td>
						<td>{{$total_dis}}</td>
						<td>{{$total_cpyable}}</td>
					</tr>
				</tfoot>
			</table>

