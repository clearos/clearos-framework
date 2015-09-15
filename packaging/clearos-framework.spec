Name: clearos-framework
Group: Development/Languages
Version: 7.1.11
Release: 1%{dist}
Summary: ClearOS framework
License: CodeIgniter and LGPLv3
Vendor: ClearFoundation
Source: %{name}-%{version}.tar.gz
Requires: clearos-base
Requires: webconfig-php >= 5.3.3
Requires: webconfig-mod_ssl
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
install -m 0644 packaging/framework-permissions.conf $RPM_BUILD_ROOT/usr/clearos/sandbox/etc/httpd/conf.d

%post
# Generate session key
if [ ! -e /var/clearos/framework/session_key ]; then
    touch /var/clearos/framework/session_key
    chmod 640 /var/clearos/framework/session_key
    chown root.webconfig /var/clearos/framework/session_key
    cat /dev/urandom | tr -dc A-Za-z0-9 | head -c32 > /var/clearos/framework/session_key
fi

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
/usr/clearos/sandbox/etc/httpd/conf.d/framework-permissions.conf 
