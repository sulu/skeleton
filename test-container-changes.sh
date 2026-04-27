#!/bin/sh

rm -rf var/cache

# dev
bin/adminconsole debug:container --format=json --show-arguments --env=dev > admin-container-dev.json
bin/adminconsole debug:container --format=json --show-arguments --show-hidden --env=dev > admin-container-dev-hidden.json
bin/websiteconsole debug:container --format=json --show-arguments --env=dev > website-container-dev.json
bin/websiteconsole debug:container --format=json --show-arguments --show-hidden --env=dev > website-container-dev-hidden.json
bin/adminconsole debug:container --parameters --format=json --env=dev > admin-parameters-dev.json
bin/websiteconsole debug:container --parameters --format=json --env=dev > website-parameters-dev.json

# test
bin/adminconsole debug:container --format=json --show-arguments --env=test > admin-container-test.json
bin/adminconsole debug:container --format=json --show-arguments --show-hidden --env=test > admin-container-test-hidden.json
bin/websiteconsole debug:container --format=json --show-arguments --env=test > website-container-test.json
bin/websiteconsole debug:container --format=json --show-arguments --show-hidden --env=test > website-container-test-hidden.json
bin/adminconsole debug:container --parameters --format=json --env=test > admin-parameters-test.json
bin/websiteconsole debug:container --parameters --format=json --env=test > website-parameters-test.json

# stage
bin/adminconsole debug:container --format=json --show-arguments --env=stage > admin-container-stage.json
bin/adminconsole debug:container --format=json --show-arguments --show-hidden --env=stage > admin-container-stage-hidden.json
bin/websiteconsole debug:container --format=json --show-arguments --env=stage > website-container-stage.json
bin/websiteconsole debug:container --format=json --show-arguments --show-hidden --env=stage > website-container-stage-hidden.json
bin/adminconsole debug:container --parameters --format=json --env=stage > admin-parameters-stage.json
bin/websiteconsole debug:container --parameters --format=json --env=stage > website-parameters-stage.json

# prod
bin/adminconsole debug:container --format=json --show-arguments --env=prod > admin-container-prod.json
bin/adminconsole debug:container --format=json --show-arguments --show-hidden --env=prod > admin-container-prod-hidden.json
bin/websiteconsole debug:container --format=json --show-arguments --env=prod > website-container-prod.json
bin/websiteconsole debug:container --format=json --show-arguments --show-hidden --env=prod > website-container-prod-hidden.json
bin/adminconsole debug:container --parameters --format=json --env=prod > admin-parameters-prod.json
bin/websiteconsole debug:container --parameters --format=json --env=prod > website-parameters-prod.json
