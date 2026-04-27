#!/bin/sh

rm -rf var/cache

# dev
bin/adminconsole debug:container --format=json --show-hidden --env=dev > admin-container-dev.json
bin/websiteconsole debug:container --format=json --show-hidden --env=dev > website-container-dev.json
bin/adminconsole debug:container --parameters --format=json --show-hidden --env=dev > admin-parameters-dev.json
bin/websiteconsole debug:container --parameters --format=json --show-hidden --env=dev > website-parameters-dev.json

# test
bin/adminconsole debug:container --format=json --show-hidden --env=test > admin-container-test.json
bin/websiteconsole debug:container --format=json --show-hidden --env=test > website-container-test.json
bin/adminconsole debug:container --parameters --format=json --show-hidden --env=test > admin-parameters-test.json
bin/websiteconsole debug:container --parameters --format=json --show-hidden --env=test > website-parameters-test.json

# stage
bin/adminconsole debug:container --format=json --show-hidden --env=stage > admin-container-stage.json
bin/websiteconsole debug:container --format=json --show-hidden --env=stage > website-container-stage.json
bin/adminconsole debug:container --parameters --format=json --show-hidden --env=stage > admin-parameters-stage.json
bin/websiteconsole debug:container --parameters --format=json --show-hidden --env=stage > website-parameters-stage.json

# prod
bin/adminconsole debug:container --format=json --show-hidden --env=prod > admin-container-prod.json
bin/websiteconsole debug:container --format=json --show-hidden --env=prod > website-container-prod.json
bin/adminconsole debug:container --parameters --format=json --show-hidden --env=prod > admin-parameters-prod.json
bin/websiteconsole debug:container --parameters --format=json --show-hidden --env=prod > website-parameters-prod.json
