MODULE_NAME := drsoftfrhookmanager
VERSION := 1.0.6
ARCHIVE := $(CURDIR)/dist/$(MODULE_NAME)-$(VERSION).zip

.PHONY: build package clean

build:
	@echo "Aucun build nécessaire pour $(MODULE_NAME)."

package: build
	mkdir -p dist
	rm -f "$(ARCHIVE)"
	cd .. && zip -rq "$(ARCHIVE)" "$(MODULE_NAME)" \
		-x "$(MODULE_NAME)/.git/*" \
		   "$(MODULE_NAME)/.claude/*" \
		   "$(MODULE_NAME)/.idea/*" \
		   "$(MODULE_NAME)/dist/*" \
		   "$(MODULE_NAME)/docs/*" \
		   "$(MODULE_NAME)/tests/*" \
		   "$(MODULE_NAME)/.gitignore" \
		   "$(MODULE_NAME)/Makefile" \
		   "$(MODULE_NAME)/*.log"
	@echo "Archive créée : $(ARCHIVE)"

clean:
	rm -rf dist
