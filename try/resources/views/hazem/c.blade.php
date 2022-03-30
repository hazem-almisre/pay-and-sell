@foreach ($comments as $value)
    @php
        if (!is_null($value->parent_id)) {
            $style = 'margin-left: 2%';
        } else {
            $style = '';
        }

    @endphp
    <div style="{{ $style }}">
        <P>{{ $value->user->name }}</P>
        <p>{{ $value->description }}</p>
        <form action="{{ route('comment.store') }}" method="POST">
            @csrf
            <input type="text" name="description">
            <input type="hidden" name="pos_id" value="{{ $pos_id }}">
            <input type="hidden" name="parent_id" value="{{ $value->id }}">
            <input type="submit" value="createcomment">
        </form>
    </div>
    @include('hazem.c', ['comments' => $value->re])
@endforeach
