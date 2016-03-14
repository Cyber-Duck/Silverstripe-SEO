# Setting Meta

## Setting Dynamic Meta 
You can use an objects properties to populate a dynamic Meta title or description tag using placeholders [].

The setDynamicTitle and setDynamicDescription functions take 3 arguments, the Meta text (required), the object (required), and the separator (default: and).

Lets assume we have a member object. We can use the properties from it to populate matching placeholders.

```php
$member = Member::currentUser();

SEO::setDynamicTitle("[FirstName] [Surname] - Site Member", $member);
```

You can also access relations using the dot syntax. If a member had a has_many relation to an Areas object and it had a class property Name we could access it as below.

```php
SEO::setDynamicDescription(
"[FirstName] [Surname] is a member of the team and specialises in [Areas.Name].", $member);
```

```
Andrew Mc Cormack is a member of the team and specialises in FirstArea, SecondArea, ThirdArea, and FourthArea
```

Relations are looped with separators (, ) and with an "and" before the last entry although you can use another separator if you want, & for example

```php
SEO::setDynamicDescription(
"[FirstName] [Surname] is a member of the team and specialises in [Areas.Name].", $member, '&');
```

```
Andrew Mc Cormack is a member of the team and specialises in FirstArea, SecondArea, ThirdArea, & FourthArea
```