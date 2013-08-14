alsaPHP
=======

Manage your alsa mixer with PHP. WIP

How to test script:
-------------------

Clone the repository :
```
git clone https://github.com/Roultabie/alsaPHP.git
```

Go to alsaPHP dir :
```
cd alsaPHP
```

Make sure the current user is in audio group
```
usermod -g audio youuser
```

And run php server :
```
php -S localhost:8000
```

In your brower type the following URL:
```
http://localhost:8000/example.php
```
Normally you can see the list of soundcards available in your system.

If a soundcard does not appear your can create an issue with the following information:

```
aplay -l
```
and
```
amixer -c cardNumber
```

Example of a list of cards :
```
    HDA Intel PCH, 0:0
        Master (limits: 0 -> 87)
            Mono
        Headphone (limits: 0 -> 87)
            Front Left
            Front Right
        Speaker (limits: 0 -> 87)
            Front Left
            Front Right
        PCM (limits: 0 -> 255)
            Front Left
            Front Right
        Mic (limits: 0 -> 31)
            Front Left
            Front Right
        Mic Boost (limits: 0 -> 31)
            Front Left
            Front Right
        IEC958 (limits: 0 -> 31)
            Mono
        Capture (limits: 0 -> 31)
            Front Left
            Front Right
        Auto-Mute Mode (limits: 0 -> 31)
            Disabled
            Enabled
        Digital (limits: 0 -> 120)
            Front Left
            Front Right
        Internal Mic (limits: 0 -> 31)
            Front Left
            Front Right
        Internal Mic Boost (limits: 0 -> 31)
            Front Left
            Front Right
