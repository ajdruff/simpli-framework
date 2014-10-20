###Changes needed:
1. simpli_forms namespace is not preserved so the message placeholder class doesnt work. it is changed to nomstock_forms, which isnt what is supposed to happen.
2. Twitter bootstrap conflict with fade in ther esponse template. you need to remove it.
3. query vars completly re-written and you probably need to rename it redirect or something that makes more sense
4. added the css config directory. i need to figure out or give guidance on how to handle directories.
5. Added a new method in tools to convert GMT time.