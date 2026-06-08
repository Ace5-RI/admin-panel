@foreach($accounts as $account)
    <tr>
        <td>{{ $account->name }}</td>
        <td>{{ $account->email }}</td>
        <td>
            {{-- Tombol Edit --}}
            <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-sm btn-primary">Edit</a>

            {{-- Tombol Hapus dengan Konfirmasi --}}
            <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus akun {{ $account->name }}?')">Hapus</button>
            </form>
        </td>
    </tr>
@endforeach