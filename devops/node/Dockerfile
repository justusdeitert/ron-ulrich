FROM node:22-alpine

RUN apk add --no-cache \
    curl \
    zsh \
    git \
    vim \
    tree

RUN sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)" "" --unattended

WORKDIR /usr/src/theme

CMD ["tail", "-f", "/dev/null"]
