import { TestBed } from '@angular/core/testing';

import { CancreateuserGuard } from './cancreateuser.guard';

describe('CancreateuserGuard', () => {
  let guard: CancreateuserGuard;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    guard = TestBed.inject(CancreateuserGuard);
  });

  it('should be created', () => {
    expect(guard).toBeTruthy();
  });
});
