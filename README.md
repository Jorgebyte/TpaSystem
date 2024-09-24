# TpaSystem
TpaSystem is a plugin with commands to teleport between players, it is simple and very useful

# Commands
- `/tpa send <player>` Send a request to the player
- `/tpa accept` Accept the player's request
- `/tpa deny` deny the player's request
- `/tpa all` make everyone come to you (only for people with permits)

# Lib
 Command: [Commando](https://github.com/LatamPMDevs/Commando)

# Messages
```YAML
# are configurable
already_tpa_pending: "§e{PLAYER}§7 already has a pending tpa, please wait for it to finish"
expired_tpa: "§cThe tpa has expired"
tpa_send_request: "§e{PLAYER}§a has received your tpa request"
tpa_target_request: "§aHey, you've received a request from:§e {PLAYER}"
tpa_accept_request: "§aYou have accepted the tpa successfully"
tpa_deny_request: "§cYou have rejected the tpa request"
no_pending_tpa: "§cIt seems that you do not have any pending tpa"
message_broadcast_tpall: "§aEveryone will be teleported in§e {TIME}§a seconds"
```

# Permissions
```YAML
  tpasystem.command:
    default: true
  tpasystem.command.all:
    default: op
```

# Contact
[![Discord Presence](https://lanyard.cnrad.dev/api/1165097093480853634?theme=dark&bg=005cff&animated=false&hideDiscrim=true&borderRadius=30px&idleMessage=Hello)](https://discord.com/users/1165097093480853634)