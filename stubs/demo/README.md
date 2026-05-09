# filament-flux Demo

Drop-in `PostResource` showcasing every component shipped by `filament-flux`.

## Install

```bash
php artisan vendor:publish --tag=filament-flux-demo
```

That copies the stubs to:

| Source                                    | Destination                                       |
|-------------------------------------------|---------------------------------------------------|
| `Post.php.stub`                            | `app/Models/Post.php`                             |
| `PostResource.php.stub`                    | `app/Filament/Resources/PostResource.php`         |
| `create_posts_table.php.stub`              | `database/migrations/{timestamp}_create_posts_table.php` |

Then:

```bash
php artisan migrate
php artisan filament:cache-components
```

You also need to scaffold the Resource pages (`ListPosts`, `CreatePost`, `EditPost`) — the easiest path is to delete the published `PostResource.php`, run `php artisan make:filament-resource Post --generate`, and copy the form/table/infolist methods from the stub back in.

## What's covered

- `FluxInput` (text, slug, email, copyable, clearable, icons)
- `FluxTextarea`
- `FluxSelect` (native)
- `FluxRadioGroup` (segmented variant)
- `FluxCheckboxGroup` (cards variant)
- `FluxSwitch`
- `FluxBadgeColumn` with state-mapped color and icon
- `FluxAvatarColumn` with closure name
- `FluxIconColumn` with state-mapped color
- `FluxAction` and `FluxDropdown` (publish / archive / delete)
- `FluxBadgeEntry` and `FluxTextEntry` for the infolist
