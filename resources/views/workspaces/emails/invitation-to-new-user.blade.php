{{__('Hi!')}}

<br><br>

{{__(':userName has invited you to join their workspace on SendPortal!', ['userName' => $invitation->workspace->owner->name])}}
{{__('If you do not already have an account, you may click the following link to get started:')}}

<br><br>

<a href="{{ url('register?invitation='.$invitation->token) }}">{{ url('register?invitation='.$invitation->token) }}</a>

<br><br>

{{__('See you soon!')}}
