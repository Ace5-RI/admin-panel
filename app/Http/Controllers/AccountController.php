use App\Models\User;

public function edit($id)
{
    $account = User::findOrFail($id);
    return view('accounts.edit', compact('account'));
}

public function update(Request $request, $id)
{
    $account = User::findOrFail($id);
    $account->update($request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,'.$id,
    ]));
    return redirect()->route('accounts.index')->with('success', 'Akun diperbarui.');
}

public function destroy($id)
{
    $account = User::findOrFail($id);
    $account->delete();
    return redirect()->route('accounts.index')->with('success', 'Akun dihapus.');
}