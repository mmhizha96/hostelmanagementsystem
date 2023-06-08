import { TestBed } from '@angular/core/testing';

import { IsSuperadminGuard } from './is-superadmin.guard';

describe('IsSuperadminGuard', () => {
  let guard: IsSuperadminGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    guard = TestBed.inject(IsSuperadminGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });
});
