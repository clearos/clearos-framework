Name: clearos-framework
Group: Development/Languages
Version: 6.1.0.beta2.1
Release: 1%{dist}
Summary: ClearOS framework
License: CodeIgniter and LGPLv3
Packager: ClearFoundation
Vendor: ClearFoundation
Source: %{name}-%{version}.tar.gz
Requires: clearos-base
Requires: webconfig-php >= 2.2.15-9
Requires(post): /sbin/service
Buildarch: noarch
Buildroot: %_tmppath/%name-%version-buildroot

%description
ClearOS framework

%prep
%setup -q
%build

%install
rm -rf $RPM_BUILD_ROOT

mkdir -p -m 755 $RPM_BUILD_ROOT/usr/clearos/apps
mkdir -p -m 755 $RPM_BUILD_ROOT/usr/clearos/sandbox
mkdir -p -m 755 $RPM_BUILD_ROOT/usr/clearos/themes
mkdir -p -m 755 $RPM_BUILD_ROOT/usr/clearos/framework/htdocs
mkdir -p -m 755 $RPM_BUILD_ROOT/usr/clearos/sandbox/etc/httpd/conf.d
mkdir -p -m 755 $RPM_BUILD_ROOT/var/clearos/framework
mkdir -p -m 1777 $RPM_BUILD_ROOT/var/clearos/framework/cache
mkdir -p -m 1777 $RPM_BUILD_ROOT/var/clearos/framework/tmp

cp -r application $RPM_BUILD_ROOT/usr/clearos/framework
cp -r htdocs $RPM_BUILD_ROOT/usr/clearos/framework
cp -r shared $RPM_BUILD_ROOT/usr/clearos/framework
cp -r system $RPM_BUILD_ROOT/usr/clearos/framework

install -m 0644 license.txt $RPM_BUILD_ROOT/usr/clearos/framework
install -m 0644 packaging/framework.conf $RPM_BUILD_ROOT/usr/clearos/sandbox/etc/httpd/conf.d

# FIXME: just a beta upgrade hack. Remove before final.
mkdir -p -m 755 $RPM_BUILD_ROOT/usr/clearos/webconfig/usr/bin
ln -s /usr/clearos/sandbox/usr/bin/php $RPM_BUILD_ROOT/usr/clearos/webconfig/usr/bin/php

%post

if [ $1 -eq 1 ]; then
	/sbin/service webconfig condrestart >/dev/null 2>&1
fi

# Generate session key
if [ ! -e /var/clearos/framework/session_key ]; then
    touch /var/clearos/framework/session_key
    chmod 640 /var/clearos/framework/session_key
    chown root.webconfig /var/clearos/framework/session_key
    cat /dev/urandom | tr -dc A-Za-z0-9 | head -c32 > /var/clearos/framework/session_key
fi

# FIXME: just a beta upgrade hack. Remove before final.
if [ -e /usr/clearos/webconfig/etc/httpd/conf/server.crt ]; then
    mv /usr/clearos/webconfig/etc/httpd/conf/server.crt /usr/clearos/sandbox/etc/httpd/conf/server.crt
fi
if [ -e /usr/clearos/webconfig/etc/httpd/conf/server.key ]; then
    mv /usr/clearos/webconfig/etc/httpd/conf/server.key /usr/clearos/sandbox/etc/httpd/conf/server.key
fi

OLDCONFS=`ls /usr/clearos/webconfig/etc/httpd/conf.d 2>/dev/null`
for OLDCONF in $OLDCONFS; do
    if [ "$OLDCONF" = "ssl.conf" ]; then
        echo "Skipping ssl.conf"
    elif [ "$OLDCONF" = "framework.conf" ]; then
        echo "Skipping framework.conf"
    else
        mv "/usr/clearos/webconfig/etc/httpd/conf.d/$OLDCONF" /usr/clearos/sandbox/etc/httpd/conf.d
    fi
done

NEWCONFS=`ls /usr/clearos/sandbox/etc/httpd/conf.d 2>/dev/null`
for NEWCONF in $NEWCONFS; do
    CHECK=`grep "/usr/clearos/webconfig" /usr/clearos/sandbox/etc/httpd/conf.d/$NEWCONF 2>/dev/null`
    if [ -n "$CHECK" ]; then
        sed -i -e 's/\/usr\/clearos\/webconfig/\/usr\/clearos\/sandbox/' /usr/clearos/sandbox/etc/httpd/conf.d/$NEWCONF
    fi
done

# FIXME: just a beta hack - need to undo clearos-base change
chkconfig auditd on
exit 0

%clean
rm -rf $RPM_BUILD_ROOT

%files
%defattr(-,root,root)
%dir /usr/clearos/apps
%dir /usr/clearos/framework
%dir /usr/clearos/sandbox
%dir /usr/clearos/themes
%dir /var/clearos/framework
%dir %attr(1777,root,root) /var/clearos/framework/cache
%dir %attr(1777,root,root) /var/clearos/framework/tmp
/usr/clearos/framework
/usr/clearos/sandbox/etc/httpd/conf.d/framework.conf
# FIXME: just a beta upgrade hack. Remove before final.
/usr/clearos/webconfig/usr/bin/php
